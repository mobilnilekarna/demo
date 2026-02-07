<?php

namespace App\Services;

use App\Models\Module;
use App\Models\User;
use App\Models\Role;
use App\Enums\TransportType;
use App\Enums\DeliveryType;
use App\Enums\PaymentType;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\DB;

class ModuleService
{
    /**
     * Mapování slug na handler metody
     */
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

    /**
     * Získá data pro zobrazení modulu podle jeho slug
     *
     * @param string $slug
     * @return array ['component' => string, 'props' => array, 'permission' => string|null]
     */
    public function getModuleData(string $slug): array
    {
        $moduleHandlers = $this->getModuleHandlers();

        // Pokud existuje handler pro tento slug
        if (isset($moduleHandlers[$slug])) {
            return $this->handleSpecificModule($slug, $moduleHandlers[$slug]);
        }

        // Výchozí handler pro obecné moduly
        return $this->handleGenericModule($slug);
    }

    /**
     * Zpracuje modul s konkrétním handlerem
     */
    private function handleSpecificModule(string $slug, string $handlerMethod): array
    {
        // Zkontrolovat, zda metoda existuje
        if (!method_exists($this, $handlerMethod)) {
            return $this->buildErrorResponse($slug, 'missing_method', [
                'method_name' => $handlerMethod,
                'class' => get_class($this),
                'module_slug' => $slug,
                'instruction' => "Je potřeba vytvořit metodu '{$handlerMethod}' v třídě " . get_class($this) . ".",
            ]);
        }

        // Získat data z handler metody
        $moduleData = $this->{$handlerMethod}();

        // Zkontrolovat, zda Vue komponenta existuje
        $componentPath = $moduleData['component'];
        $filePath = resource_path("js/Pages/{$componentPath}.vue");

        if (!File::exists($filePath)) {
            return $this->buildErrorResponse($slug, 'missing_vue_file', [
                'expected_path' => $filePath,
                'component_path' => $componentPath,
                'vue_file' => basename($filePath),
                'module_slug' => $slug,
                'handler_method' => $handlerMethod,
                'instruction' => "Metoda '{$handlerMethod}' existuje, ale Vue komponenta '{$componentPath}' nebyla nalezena. Je potřeba vytvořit soubor na cestě: {$filePath}",
            ]);
        }

        return $moduleData;
    }

    /**
     * Handler pro obecné moduly (fallback)
     */
    private function handleGenericModule(string $slug): array
    {
        $module = Module::where('slug', $slug)->where('active', true)->firstOrFail();
        $moduleName = $this->slugToPascalCase($slug);
        $componentPath = "Dashboard/Modules/{$moduleName}";
        $filePath = resource_path("js/Pages/{$componentPath}.vue");
        $handlerMethod = 'handle' . $moduleName;

        // Zkontrolovat, zda existuje handler metoda a Vue soubor
        $methodExists = method_exists($this, $handlerMethod);
        $vueFileExists = File::exists($filePath);

        // Pokud chybí metoda nebo Vue soubor
        if (!$methodExists || !$vueFileExists) {
            return $this->buildGenericModuleErrorResponse($module, $slug, $handlerMethod, $componentPath, $filePath, $methodExists, $vueFileExists);
        }

        // Pokud existuje handler metoda, použít ji
        if ($methodExists) {
            return $this->{$handlerMethod}();
        }

        // Výchozí odpověď pro obecný modul
        return [
            'component' => $componentPath,
            'props' => [
                'module' => [
                    'id' => $module->id,
                    'name' => $module->name,
                    'slug' => $module->slug,
                    'description' => $module->description,
                ],
            ],
            'permission' => null,
        ];
    }

    /**
     * Vytvoří chybovou odpověď pro obecný modul
     */
    private function buildGenericModuleErrorResponse(
        Module $module,
        string $slug,
        string $handlerMethod,
        string $componentPath,
        string $filePath,
        bool $methodExists,
        bool $vueFileExists
    ): array {
        $errors = [];

        if (!$methodExists) {
            $errors[] = [
                'type' => 'missing_method',
                'message' => "Chybí metoda: {$handlerMethod}",
                'details' => [
                    'method_name' => $handlerMethod,
                    'class' => get_class($this),
                    'module_slug' => $slug,
                    'instruction' => "Je potřeba vytvořit metodu '{$handlerMethod}' v třídě " . get_class($this) . ".",
                ],
            ];
        }

        if (!$vueFileExists) {
            $errors[] = [
                'type' => 'missing_vue_file',
                'message' => "Chybí Vue soubor: {$componentPath}.vue",
                'details' => [
                    'expected_path' => $filePath,
                    'component_path' => $componentPath,
                    'vue_file' => basename($filePath),
                    'module_slug' => $slug,
                    'handler_method' => $handlerMethod,
                    'instruction' => "Je potřeba vytvořit Vue komponentu na cestě: {$filePath}",
                ],
            ];
        }

        return [
            'component' => 'Dashboard/ModuleView',
            'props' => [
                'module' => [
                    'id' => $module->id,
                    'name' => $module->name,
                    'slug' => $module->slug,
                    'description' => $module->description,
                ],
                'errors' => $errors,
            ],
            'permission' => null,
        ];
    }

    /**
     * Vytvoří chybovou odpověď
     */
    private function buildErrorResponse(string $slug, string $errorType, array $details): array
    {
        $module = Module::where('slug', $slug)->where('active', true)->firstOrFail();

        return [
            'component' => 'Dashboard/ModuleView',
            'props' => [
                'module' => [
                    'id' => $module->id,
                    'name' => $module->name,
                    'slug' => $module->slug,
                    'description' => $module->description,
                ],
                'errors' => [
                    [
                        'type' => $errorType,
                        'message' => $details['message'] ?? "Chyba: {$errorType}",
                        'details' => $details,
                    ],
                ],
            ],
            'permission' => null,
        ];
    }

    /**
     * Převést slug na PascalCase
     */
    private function slugToPascalCase(string $slug): string
    {
        return str_replace(' ', '', ucwords(str_replace(['-', '_'], ' ', $slug)));
    }

    /**
     * Handler pro seznam uživatelů
     */
    private function handleUsersList(): array
    {
        $users = User::with('roles')->get()->map(function ($user) {
            return [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'roles' => $user->roles->pluck('name'),
                'created_at' => $user->created_at->format('d.m.Y H:i'),
            ];
        });

        $roles = Role::all()->pluck('name');

        return [
            'component' => 'Dashboard/Users/List',
            'props' => [
                'users' => $users,
                'roles' => $roles,
            ],
            'permission' => null,
        ];
    }

    /**
     * Handler pro role a oprávnění
     */
    private function handleUsersRoles(): array
    {
        $roles = $this->formatRoles();
        $permissions = $this->formatPermissionsGrouped();
        $users = $this->formatUsers();

        return [
            'component' => 'Dashboard/RolesPermissions',
            'props' => [
                'roles' => $roles,
                'permissions' => $permissions,
                'users' => $users,
                'defaultTab' => 'roles',
            ],
            'permission' => null,
        ];
    }

    /**
     * Handler pro oprávnění
     */
    private function handleUsersPermissions(): array
    {
        $permissions = $this->formatPermissionsGrouped();
        $allPermissions = $this->formatAllPermissions();

        return [
            'component' => 'Dashboard/Permissions',
            'props' => [
                'permissions' => $permissions,
                'allPermissions' => $allPermissions,
            ],
            'permission' => null,
        ];
    }

    /**
     * Handler pro dopravy
     */
    private function handleTransports(): array
    {
        $transportService = new TransportService();
        $transports = $this->formatTransports($transportService->getAll());
        $transportTypes = $this->formatEnumTypes(TransportType::class);
        $deliveryTypes = $this->formatEnumTypes(DeliveryType::class);

        return [
            'component' => 'Dashboard/Transports/List',
            'props' => [
                'transports' => $transports,
                'transportTypes' => $transportTypes,
                'deliveryTypes' => $deliveryTypes,
            ],
            'permission' => 'view-transports',
        ];
    }

    /**
     * Handler pro platby
     */
    private function handlePayments(): array
    {
        $paymentService = new PaymentService();
        $payments = $this->formatPayments($paymentService->getAll());
        $paymentTypes = $this->formatEnumTypes(PaymentType::class);

        return [
            'component' => 'Dashboard/Payments/List',
            'props' => [
                'payments' => $payments,
                'paymentTypes' => $paymentTypes,
            ],
            'permission' => 'view-payments',
        ];
    }

    /**
     * Handler pro kontakty
     */
    private function handleTicketsList(): array
    {
        return [
            'component' => 'Dashboard/Tickets/List',
            'permission' => 'view-tickets',
        ];
    }

    /**
     * Formátuje role s oprávněními
     */
    private function formatRoles()
    {
        return Role::with('permissions')->get()->map(function ($role) {
            return [
                'id' => $role->id,
                'name' => $role->name,
                'permissions' => $role->permissions->pluck('name'),
                'users_count' => $role->users()->count(),
            ];
        });
    }

    /**
     * Formátuje oprávnění seskupená podle prefixu
     */
    private function formatPermissionsGrouped()
    {
        return Permission::all()->groupBy(function ($permission) {
            $parts = explode('-', $permission->name);
            return $parts[0] ?? 'other';
        })->map(function ($group) {
            return $group->map(function ($permission) {
                return [
                    'id' => $permission->id,
                    'name' => $permission->name,
                ];
            });
        });
    }

    /**
     * Formátuje všechny oprávnění
     */
    private function formatAllPermissions()
    {
        return Permission::all()->map(function ($permission) {
            return [
                'id' => $permission->id,
                'name' => $permission->name,
            ];
        });
    }

    /**
     * Formátuje uživatele s rolemi
     */
    private function formatUsers()
    {
        return User::with('roles')->get()->map(function ($user) {
            return [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'roles' => $user->roles->pluck('name'),
            ];
        });
    }

    /**
     * Formátuje dopravy
     */
    private function formatTransports($transports)
    {
        return $transports->map(function ($transport) {
            return [
                'id' => $transport->id,
                'name' => $transport->name,
                'type' => $transport->type,
                'type_delivery' => $transport->type_delivery,
                'price' => (float) $transport->price,
                'free_from' => $transport->free_from ? (float) $transport->free_from : null,
                'order' => $transport->order,
                'active' => $transport->active,
                'image' => $transport->image,
                'created_at' => $transport->created_at->format('d.m.Y H:i'),
            ];
        });
    }

    /**
     * Formátuje platby
     */
    private function formatPayments($payments)
    {
        return $payments->map(function ($payment) {
            return [
                'id' => $payment->id,
                'name' => $payment->name,
                'type' => $payment->type,
                'price' => (float) $payment->price,
                'order' => $payment->order,
                'active' => $payment->active,
                'created_at' => $payment->created_at->format('d.m.Y H:i'),
            ];
        });
    }

    /**
     * Formátuje enum typy pro frontend
     */
    private function formatEnumTypes(string $enumClass)
    {
        return collect($enumClass::cases())->map(function ($type) {
            return [
                'value' => $type->value,
                'label' => $type->name(),
            ];
        });
    }

    /**
     * Zkontroluje, zda má uživatel přístup k modulu
     *
     * @param Module $module
     * @param User $user
     * @return bool
     */
    public function hasAccess(Module $module, User $user): bool
    {
        $roleIds = $user->roles()->pluck('roles.id')->toArray();

        return DB::table('module_role')
            ->where('module_id', $module->id)
            ->whereIn('role_id', $roleIds)
            ->exists();
    }
}

