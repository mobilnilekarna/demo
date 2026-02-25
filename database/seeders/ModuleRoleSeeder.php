<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Module;
use App\Models\Role;

class ModuleRoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Všechna ID modulů
        $allModules = Module::all();

        // ===== Super Admin – všechny moduly =====
        $superAdmin = Role::where('name', 'Super Admin')->first();
        if ($superAdmin) {
            $superAdmin->modules()->sync($allModules->pluck('id')->toArray());
        }

        // ===== Admin – všechny moduly kromě některých kritických nastavení =====
        $admin = Role::where('name', 'Admin')->first();
        if ($admin) {
            $adminModules = $allModules->filter(function ($module) {
                // Tady můžeš vyjmenovat moduly, které Admin vidět nemá
                $excludedSlugs = ['settings-updates'];
                return !in_array($module->slug, $excludedSlugs);
            });

            $admin->modules()->sync($adminModules->pluck('id')->toArray());
        }

        // ===== Manager – omezený přístup (pouze e-shop a obsah) =====
        $manager = Role::where('name', 'Manager')->first();
        if ($manager) {
            $eshopModule = Module::where('slug', 'e-shop')->first();
            $obsahModule = Module::where('slug', 'content')->first();

            $managerModuleIds = collect();

            if ($eshopModule) {
                $managerModuleIds->push($eshopModule->id);
                $managerModuleIds = $managerModuleIds->merge(
                    $eshopModule->descendants()->get()->pluck('id')
                );
            }

            if ($obsahModule) {
                $managerModuleIds->push($obsahModule->id);
                $managerModuleIds = $managerModuleIds->merge(
                    $obsahModule->descendants()->get()->pluck('id')
                );
            }

            $manager->modules()->sync($managerModuleIds->unique()->values()->toArray());
        }

        // ===== User – základní přístup (pouze přehled) =====
        $userRole = Role::where('name', 'User')->first();
        if ($userRole) {
            $prehledModule = Module::where('slug', 'e-shop-overview')->first();
            $eshopModule = Module::where('slug', 'e-shop')->first();

            $userModuleIds = collect();
            if ($eshopModule) {
                $userModuleIds->push($eshopModule->id);
            }
            if ($prehledModule) {
                $userModuleIds->push($prehledModule->id);
            }

            $userRole->modules()->sync($userModuleIds->unique()->values()->toArray());
        }
    }
}


