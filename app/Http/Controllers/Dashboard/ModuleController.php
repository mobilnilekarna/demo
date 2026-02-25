<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Module;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Inertia\Inertia;

class ModuleController extends Controller
{
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            if (!$request->user() || !$request->user()->hasRole('Super Admin')) {
                abort(403, 'Přístup zamítnut. Tato sekce je dostupná pouze pro Super Admina.');
            }
            return $next($request);
        });
    }

    /**
     * Zobrazí stránku pro správu modulů
     */
    public function index()
    {
        if (!auth()->user()->hasRole('Super Admin')) {
            abort(403, 'Přístup zamítnut. Tato sekce je dostupná pouze pro Super Admina.');
        }

        // Načíst VŠECHNY moduly - toTree() potřebuje všechny moduly pro vytvoření hierarchie
        $allModules = Module::tree()
            ->orderBy('rank')
            ->orderBy('order')
            ->get();

        // Vytvořit strom z VŠECH modulů - toTree() automaticky vytvoří hierarchii s children
        $tree = $allModules->toTree();

        // Filtrovat pouze hlavní moduly (parent_id === null) - jejich children jsou už v hierarchii
        $modules = $tree
            ->filter(function ($module) {
                return $module->parent_id === null;
            })
            ->map(function ($module) {
                return $this->formatModule($module);
            })
            ->values();

        $allRoles = Role::with('users')->get();

        $roles = $allRoles->map(function ($role) {
            return [
                'id' => $role->id,
                'name' => $role->name,
                'users_count' => $role->users->count(),
            ];
        });

        // Získat všechny moduly (plochý seznam pro výběr)
        $allModulesFlat = $allModules->map(function ($module) {
            return $this->formatModuleFlat($module);
        });

        // Získat moduly přiřazené k rolím
        $modulesByRole = [];
        foreach ($roles as $role) {
            $roleModel = Role::find($role['id']);
            $moduleIds = $roleModel->modules()->pluck('modules.id')->toArray();
            $modulesByRole[$role['id']] = $moduleIds;
        }

        return Inertia::render('Dashboard/ModulesManagement', [
            'modules' => $modules,
            'roles' => $roles,
            'allModules' => $allModulesFlat,
            'modulesByRole' => $modulesByRole,
        ]);
    }

    /**
     * Vytvoří nový modul
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:modules,slug',
            'description' => 'nullable|string',
            'icon' => 'nullable|string|max:255',
            'route' => 'nullable|string|max:255',
            'parent_id' => 'nullable|exists:modules,id',
            'active' => 'boolean',
            'rank' => 'integer',
        ]);

        $module = Module::create($validated);

        // Vytvořit Vue soubor, pokud neexistuje
        $this->createVueFileIfNeeded($module);

        return redirect()->route('dashboard.modules.index')
            ->with('success', 'Modul byl úspěšně vytvořen.');
    }

    /**
     * Aktualizuje modul
     */
    public function update(Request $request, Module $module)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:modules,slug,' . $module->id,
            'description' => 'nullable|string',
            'icon' => 'nullable|string|max:255',
            'route' => 'nullable|string|max:255',
            'parent_id' => 'nullable|exists:modules,id',
            'active' => 'boolean',
            'rank' => 'integer',
        ]);

        $module->update($validated);

        return redirect()->route('dashboard.modules.index')
            ->with('success', 'Modul byl úspěšně aktualizován.');
    }

    /**
     * Smaže modul
     */
    public function destroy(Module $module)
    {
        $module->delete();

        return redirect()->route('dashboard.modules.index')
            ->with('success', 'Modul byl úspěšně smazán.');
    }

    /**
     * Aktualizuje pořadí modulů
     */
    public function updateRank(Request $request)
    {
        $modules = $request->input('modules', []);

        foreach ($modules as $moduleData) {
            Module::where('id', $moduleData['id'])->update([
                'rank' => $moduleData['rank'] ?? 0,
            ]);
        }

        return response()->json(['success' => true]);
    }

    /**
     * Přiřadí moduly k roli
     */
    public function assignModulesToRole(Request $request, Role $role)
    {
        $moduleIds = $request->input('module_ids', []);

        $role->modules()->sync($moduleIds);

        return redirect()->route('dashboard.modules.index')
            ->with('success', 'Moduly byly úspěšně přiřazeny k roli.');
    }

    /**
     * Formátuje modul pro frontend
     */
    private function formatModule($module): array
    {
        $formatted = [
            'id' => $module->id,
            'name' => $module->name,
            'slug' => $module->slug,
            'description' => $module->description,
            'icon' => $module->icon,
            'route' => $module->route,
            'parent_id' => $module->parent_id,
            'active' => $module->active,
            'order' => $module->order,
            'rank' => $module->rank,
        ];

        if ($module->children && $module->children->isNotEmpty()) {
            $formatted['children'] = $module->children->map(function ($child) {
                return $this->formatModule($child);
            })->values();
        }

        return $formatted;
    }

    /**
     * Formátuje modul jako plochý objekt (bez children)
     */
    private function formatModuleFlat($module): array
    {
        return [
            'id' => $module->id,
            'name' => $module->name,
            'slug' => $module->slug,
            'parent_id' => $module->parent_id,
        ];
    }

    /**
     * Vytvoří Vue soubor pro modul, pokud neexistuje
     */
    private function createVueFileIfNeeded(Module $module): void
    {
        $moduleName = $this->slugToPascalCase($module->slug);
        $componentPath = "Dashboard/Modules/{$moduleName}";
        $filePath = resource_path("js/Pages/{$componentPath}.vue");

        if (!File::exists($filePath)) {
            $vueContent = $this->generateVueTemplate($module->name, $module->slug);
            File::ensureDirectoryExists(dirname($filePath));
            File::put($filePath, $vueContent);
        }
    }

    /**
     * Převést slug na PascalCase
     */
    private function slugToPascalCase(string $slug): string
    {
        return str_replace(' ', '', ucwords(str_replace(['-', '_'], ' ', $slug)));
    }

    /**
     * Vygenerovat Vue template
     */
    private function generateVueTemplate(string $name, string $slug): string
    {
        $componentName = $this->slugToPascalCase($slug);

        return <<<VUE
<template>
  <DashboardLayout>
    <div class="p-4">
      <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900 dark:text-gray-100">{$name}</h1>
        <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">
          Správa modulu: {$name}
        </p>
      </div>

      <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
        <p class="text-gray-600 dark:text-gray-400">
          Toto je stránka pro modul <strong>{$name}</strong> (slug: <code>{$slug}</code>).
        </p>
        <p class="text-gray-600 dark:text-gray-400 mt-2">
          Tento soubor byl automaticky vygenerován při vytvoření modulu.
        </p>
      </div>
    </div>
  </DashboardLayout>
</template>

<script setup>
import DashboardLayout from '@/Layouts/DashboardLayout.vue'

defineProps({
  module: {
    type: Object,
    default: null
  }
})
</script>

VUE;
    }
}
