<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Pro konzistentní seed vyčistit související tabulky
        Schema::disableForeignKeyConstraints();

        // Naše vlastní pivot tabulka module_role (role -> modul)
        if (Schema::hasTable('module_role')) {
            DB::table('module_role')->truncate();
        }

        // Tabulky balíčku spatie/permission
        if (Schema::hasTable('model_has_roles')) {
            DB::table('model_has_roles')->truncate();
        }
        if (Schema::hasTable('role_has_permissions')) {
            DB::table('role_has_permissions')->truncate();
        }
        if (Schema::hasTable('permissions')) {
            DB::table('permissions')->truncate();
        }
        if (Schema::hasTable('roles')) {
            DB::table('roles')->truncate();
        }

        Schema::enableForeignKeyConstraints();

        // Vytvoření oprávnění
        $permissions = [
            // Dashboard
            'view-dashboard',

            // Moduly
            'view-modules',
            'create-modules',
            'edit-modules',
            'delete-modules',
            'manage-modules',

            // Uživatelé
            'view-users',
            'create-users',
            'edit-users',
            'delete-users',

            // Role a oprávnění
            'view-roles',
            'create-roles',
            'edit-roles',
            'delete-roles',
            'assign-roles',

            // Produkty
            'view-products',
            'create-products',
            'edit-products',
            'delete-products',

            // Objednávky
            'view-orders',
            'create-orders',
            'edit-orders',
            'delete-orders',

            // Nastavení
            'view-settings',
            'create-settings',
            'edit-settings',
            'delete-settings',

            // E-shop moduly
            'view-e-shop',
            'create-e-shop',
            'edit-e-shop',
            'delete-e-shop',
            'view-bonuses',
            'create-bonuses',
            'edit-bonuses',
            'delete-bonuses',
            'view-e-prescription',
            'create-e-prescription',
            'edit-e-prescription',
            'delete-e-prescription',
            'view-customers',
            'create-customers',
            'edit-customers',
            'delete-customers',

            // Obsah moduly
            'view-content',
            'create-content',
            'edit-content',
            'delete-content',
            'view-content-banners',
            'create-content-banners',
            'edit-content-banners',
            'delete-content-banners',
            'view-content-pages',
            'create-content-pages',
            'edit-content-pages',
            'delete-content-pages',
            'view-content-articles',
            'create-content-articles',
            'edit-content-articles',
            'delete-content-articles',
            'view-content-terms',
            'create-content-terms',
            'edit-content-terms',
            'delete-content-terms',
            'view-content-abandoned-carts',
            'create-content-abandoned-carts',
            'edit-content-abandoned-carts',
            'delete-content-abandoned-carts',
            'view-content-files',
            'create-content-files',
            'edit-content-files',
            'delete-content-files',

            // Nastavení moduly
            'view-settings-updates',
            'create-settings-updates',
            'edit-settings-updates',
            'delete-settings-updates',
            'view-settings-constants',
            'create-settings-constants',
            'edit-settings-constants',
            'delete-settings-constants',
            'view-transports',
            'create-transports',
            'edit-transports',
            'delete-transports',
            'view-payments',
            'create-payments',
            'edit-payments',
            'delete-payments',
            'view-settings-branches',
            'create-settings-branches',
            'edit-settings-branches',
            'delete-settings-branches',
            'view-settings-emails',
            'create-settings-emails',
            'edit-settings-emails',
            'delete-settings-emails',

            // PharmData moduly
            'view-pharmdata',
            'create-pharmdata',
            'edit-pharmdata',
            'delete-pharmdata',
            'view-pharmdata-atc-groups',
            'create-pharmdata-atc-groups',
            'edit-pharmdata-atc-groups',
            'delete-pharmdata-atc-groups',
            'view-pharmdata-products',
            'create-pharmdata-products',
            'edit-pharmdata-products',
            'delete-pharmdata-products',
            'view-pharmdata-categories',
            'create-pharmdata-categories',
            'edit-pharmdata-categories',
            'delete-pharmdata-categories',
            'view-pharmdata-manufacturers',
            'create-pharmdata-manufacturers',
            'edit-pharmdata-manufacturers',
            'delete-pharmdata-manufacturers',
            'view-pharmdata-otc',
            'create-pharmdata-otc',
            'edit-pharmdata-otc',
            'delete-pharmdata-otc',

            // Tikety moduly
            'view-tickets',
            'create-tickets',
            'edit-tickets',
            'delete-tickets',
        ];

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }

        // Vytvoření rolí
        $superAdmin = Role::create(['name' => 'Super Admin']);
        $admin = Role::create(['name' => 'Admin']);
        $manager = Role::create(['name' => 'Manager']);
        $user = Role::create(['name' => 'User']);

        // Přiřazení oprávnění k rolím

        // Super Admin - všechna oprávnění
        $superAdmin->givePermissionTo(Permission::all());

        // Admin - většina oprávnění kromě některých kritických
        $admin->givePermissionTo([
            'view-dashboard',
            'view-modules',
            'manage-modules',
            'edit-modules',
            'view-users',
            'create-users',
            'edit-users',
            'view-roles',
            'view-products',
            'create-products',
            'edit-products',
            'view-orders',
            'edit-orders',
            'view-settings',
            'edit-settings',
        ]);

        // Manager - omezená oprávnění
        $manager->givePermissionTo([
            'view-dashboard',
            'view-modules',
            'view-users',
            'view-products',
            'create-products',
            'edit-products',
            'view-orders',
            'edit-orders',
        ]);

        // User - základní oprávnění
        $user->givePermissionTo([
            'view-dashboard',
            'view-products',
            'view-orders',
        ]);

        // Vytvoření uživatelů
        $users = [
            [
                'name' => 'Super Admin',
                'email' => 'superadmin@lekarna.cz',
                'password' => Hash::make('SuperAdmin123!'),
                'role' => 'Super Admin',
            ],
            [
                'name' => 'Admin',
                'email' => 'admin@lekarna.cz',
                'password' => Hash::make('Admin123!'),
                'role' => 'Admin',
            ],
            [
                'name' => 'Manager',
                'email' => 'manager@lekarna.cz',
                'password' => Hash::make('Manager123!'),
                'role' => 'Manager',
            ],
            [
                'name' => 'Uživatel',
                'email' => 'user@lekarna.cz',
                'password' => Hash::make('User123!'),
                'role' => 'User',
            ],
        ];

        foreach ($users as $userData) {
            $user = User::create([
                'name' => $userData['name'],
                'email' => $userData['email'],
                'password' => $userData['password'],
            ]);
            $user->assignRole($userData['role']);
        }

        $this->command->info('Role, oprávnění a uživatelé byly úspěšně vytvořeny!');
        $this->command->info('');
        $this->command->info('Přihlašovací údaje:');
        $this->command->info('==================');
        $this->command->info('1. Super Admin');
        $this->command->info('   Email: superadmin@lekarna.cz');
        $this->command->info('   Heslo: SuperAdmin123!');
        $this->command->info('');
        $this->command->info('2. Admin');
        $this->command->info('   Email: admin@lekarna.cz');
        $this->command->info('   Heslo: Admin123!');
        $this->command->info('');
        $this->command->info('3. Manager');
        $this->command->info('   Email: manager@lekarna.cz');
        $this->command->info('   Heslo: Manager123!');
        $this->command->info('');
        $this->command->info('4. User');
        $this->command->info('   Email: user@lekarna.cz');
        $this->command->info('   Heslo: User123!');
    }
}
