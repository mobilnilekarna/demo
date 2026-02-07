<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Module;
use App\Services\ModuleService;
use Illuminate\Support\Facades\File;
use Inertia\Inertia;
use ReflectionClass;

class ModuleDisplayController extends Controller
{
    protected $moduleService;

    // Mapování slug na handler metody (shodné s ModuleService)
    private function getModuleHandlers(): array
    {
        return [
            'users-list' => 'handleUsersList',
            'users-roles' => 'handleUsersRoles',
            'users-permissions' => 'handleUsersPermissions',
            'settings-transports' => 'handleTransports',
            'settings-payments' => 'handlePayments',
        ];
    }

    public function __construct(ModuleService $moduleService)
    {
        $this->moduleService = $moduleService;
    }

    /**
     * Zobrazí modul podle slug
     */
    public function show(string $slug)
    {
        $module = Module::where('slug', $slug)->where('active', true)->firstOrFail();
        $user = auth()->user();

        if (!$this->moduleService->hasAccess($module, $user)) {
            abort(403, 'Nemáte přístup k tomuto modulu.');
        }

        // Kontrola existence metody a Vue souboru
        $errors = $this->validateModule($slug, $module);
        if (!empty($errors)) {
            return $this->renderErrorView($module, $errors);
        }

        // Načíst a zobrazit data modulu
        $moduleData = $this->moduleService->getModuleData($slug);

        if ($moduleData['permission'] && !$user->can($moduleData['permission'])) {
            abort(403, 'Nemáte oprávnění k zobrazení tohoto modulu.');
        }

        return Inertia::render($moduleData['component'], $moduleData['props']);
    }

    /**
     * Vykreslí error view s chybami
     */
    private function renderErrorView(Module $module, array $errors)
    {
        return Inertia::render('Dashboard/ModuleView', [
            'module' => $this->formatModule($module),
            'error' => ['errors' => $errors],
        ]);
    }

    /**
     * Formátuje modul pro předání do view
     */
    private function formatModule(Module $module): array
    {
        return [
            'id' => $module->id,
            'name' => $module->name,
            'slug' => $module->slug,
            'description' => $module->description,
        ];
    }

    /**
     * Ověří, zda modul má existující metodu v ModuleService a Vue soubor
     */
    private function validateModule(string $slug, Module $module): array
    {
        $errors = [];
        $handlers = $this->getModuleHandlers();
        $isSpecificModule = isset($handlers[$slug]);
        [$handlerMethod, $componentPath] = $this->determineHandlerAndPath($slug);

        // Kontrola metody
        if (!$this->methodExists($handlerMethod)) {
            $errors['missing_method'] = $this->buildMethodError($slug, $handlerMethod);
            // Pro specifické moduly nemůžeme zkontrolovat Vue bez metody
            if ($isSpecificModule) {
                return $errors;
            }
        } else {
            // Získat component path z metody (pokud existuje)
            $componentPath = $this->getComponentPathFromMethod($handlerMethod) ?? $componentPath;
        }

        // Kontrola Vue souboru
        if ($componentPath && !$this->vueFileExists($componentPath)) {
            $errors['missing_vue_file'] = $this->buildVueFileError($slug, $componentPath, $handlerMethod);
        }

        return $errors;
    }

    /**
     * Zkontroluje, zda Vue soubor existuje
     */
    private function vueFileExists(string $componentPath): bool
    {
        return File::exists(resource_path("js/Pages/{$componentPath}.vue"));
    }

    /**
     * Určí handler metodu a výchozí component path
     */
    private function determineHandlerAndPath(string $slug): array
    {
        $handlers = $this->getModuleHandlers();

        if (isset($handlers[$slug])) {
            return [$handlers[$slug], null];
        }

        $moduleName = $this->slugToPascalCase($slug);
        return ['handle' . $moduleName, "Dashboard/Modules/{$moduleName}"];
    }

    /**
     * Získá ReflectionClass pro ModuleService
     */
    private function getServiceReflection(): ReflectionClass
    {
        return new ReflectionClass($this->moduleService);
    }

    /**
     * Zkontroluje, zda metoda existuje v ModuleService
     */
    private function methodExists(string $methodName): bool
    {
        return $this->getServiceReflection()->hasMethod($methodName);
    }

    /**
     * Získá component path z handler metody
     */
    private function getComponentPathFromMethod(string $methodName): ?string
    {
        try {
            $reflection = $this->getServiceReflection();
            $method = $reflection->getMethod($methodName);
            $method->setAccessible(true);
            $data = $method->invoke($this->moduleService);
            return $data['component'] ?? null;
        } catch (\Exception $e) {
            return null;
        }
    }

    /**
     * Vytvoří chybovou strukturu pro chybějící metodu
     */
    private function buildMethodError(string $slug, string $methodName): array
    {
        $reflection = $this->getServiceReflection();
        $filePath = $reflection->getFileName();
        $className = get_class($this->moduleService);

        return [
            'method_name' => $methodName,
            'class' => $className,
            'class_path' => str_replace('\\', '/', $className),
            'file_path' => $filePath,
            'module_slug' => $slug,
            'instruction' => "Je potřeba vytvořit metodu '{$methodName}' v třídě {$className} ({$filePath}).",
        ];
    }

    /**
     * Vytvoří chybovou strukturu pro chybějící Vue soubor
     */
    private function buildVueFileError(string $slug, string $componentPath, string $handlerMethod): array
    {
        $relativePath = "resources/js/Pages/{$componentPath}.vue";
        $absolutePath = resource_path($relativePath);

        return [
            'component_path' => $componentPath,
            'relative_path' => $relativePath,
            'file_path' => $absolutePath,
            'vue_file' => basename($absolutePath),
            'module_slug' => $slug,
            'handler_method' => $handlerMethod,
            'instruction' => "Je potřeba vytvořit Vue komponentu na cestě: {$relativePath} (absolutní cesta: {$absolutePath})",
        ];
    }

    /**
     * Převést slug na PascalCase
     *
     * @param string $slug
     * @return string
     */
    private function slugToPascalCase(string $slug): string
    {
        return str_replace(' ', '', ucwords(str_replace(['-', '_'], ' ', $slug)));
    }
}

