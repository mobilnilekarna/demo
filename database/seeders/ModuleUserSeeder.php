<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Module;
use App\Models\User;
use Spatie\Permission\Models\Role;

class ModuleUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Získání všech modulů
        $allModules = Module::all();
        
        // Super Admin - přístup ke všem modulům
        $superAdminRole = Role::where('name', 'Super Admin')->first();
        if ($superAdminRole) {
            $superAdminUsers = $superAdminRole->users;
            foreach ($superAdminUsers as $user) {
                $user->modules()->sync($allModules->pluck('id')->toArray());
            }
        }
        
        // Admin - přístup k většině modulů (kromě některých kritických)
        $adminRole = Role::where('name', 'Admin')->first();
        if ($adminRole) {
            $adminUsers = $adminRole->users;
            // Admin má přístup ke všem modulům kromě některých kritických nastavení
            $adminModules = $allModules->filter(function ($module) {
                // Vyloučit některé kritické moduly, pokud existují
                $excludedSlugs = ['settings-updates'];
                return !in_array($module->slug, $excludedSlugs);
            });
            foreach ($adminUsers as $user) {
                $user->modules()->sync($adminModules->pluck('id')->toArray());
            }
        }
        
        // Manager - omezený přístup (pouze e-shop a obsah)
        $managerRole = Role::where('name', 'Manager')->first();
        if ($managerRole) {
            $managerUsers = $managerRole->users;
            // Najít hlavní moduly e-shop a obsah
            $eshopModule = Module::where('slug', 'e-shop')->first();
            $obsahModule = Module::where('slug', 'content')->first();
            
            $managerModuleIds = collect();
            if ($eshopModule) {
                // Přidat e-shop a všechny jeho podmoduly
                $managerModuleIds->push($eshopModule->id);
                $managerModuleIds = $managerModuleIds->merge(
                    $eshopModule->descendants()->get()->pluck('id')
                );
            }
            if ($obsahModule) {
                // Přidat obsah a všechny jeho podmoduly
                $managerModuleIds->push($obsahModule->id);
                $managerModuleIds = $managerModuleIds->merge(
                    $obsahModule->descendants()->get()->pluck('id')
                );
            }
            
            foreach ($managerUsers as $user) {
                $user->modules()->sync($managerModuleIds->unique()->values()->toArray());
            }
        }
        
        // User - základní přístup (pouze přehled)
        $userRole = Role::where('name', 'User')->first();
        if ($userRole) {
            $regularUsers = $userRole->users;
            // Najít modul přehledu
            $prehledModule = Module::where('slug', 'e-shop-overview')->first();
            $eshopModule = Module::where('slug', 'e-shop')->first();
            
            $userModuleIds = collect();
            if ($eshopModule) {
                $userModuleIds->push($eshopModule->id);
            }
            if ($prehledModule) {
                $userModuleIds->push($prehledModule->id);
            }
            
            foreach ($regularUsers as $user) {
                $user->modules()->sync($userModuleIds->unique()->values()->toArray());
            }
        }
        
        $this->command->info('Moduly byly úspěšně přiřazeny uživatelům podle rolí!');
    }
}
