<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Vytvoření rolí, oprávnění a uživatelů
        $this->call(RolesAndPermissionsSeeder::class);

        // 2. Načtení modulů administrace
        $this->call(ModulesSeeder::class);

        // 3. Přiřazení modulů rolím (role -> modul) do tabulky module_role
        $this->call(ModuleRoleSeeder::class);

        // 4. Vytvoření doprav a plateb
        $this->call(TransportPaymentSeeder::class);

        // 6. Vytvoření doprav
        $this->call(ShippingSeeder::class);
    }
}
