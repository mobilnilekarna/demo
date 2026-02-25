<?php

namespace App\Http\Middleware;

use Illuminate\Http\Request;
use Inertia\Middleware;
use App\Facades\BasketFacade;
use App\Http\Controllers\Pages\ProductController as ProductControllerPages;
use App\Models\Module;

class HandleInertiaRequests extends Middleware
{
    /**
     * The root template that's loaded on the first page visit.
     *
     * @see https://inertiajs.com/server-side-setup#root-template
     *
     * @var string
     */
    protected $rootView = 'app';

    /**
     * Determines the current asset version.
     *
     * @see https://inertiajs.com/asset-versioning
     */
    public function version(Request $request): ?string
    {
        return parent::version($request);
    }

    /**
     * Define the props that are shared by default.
     *
     * @see https://inertiajs.com/shared-data
     *
     * @return array<string, mixed>
     */
    public function share(Request $request): array
    {
          return array_merge(parent::share($request), [
            // Synchronously...
            'appName' => 'Aplikace drug store',
            'locale' => app()->getLocale(),
            'auth' => [
                'user' => $request->user() ? [
                    'id' => $request->user()->id,
                    'name' => $request->user()->name,
                    'email' => $request->user()->email,
                    // Předat také role pro potřeby frontendu (např. zobrazení sekce Moduly pro Super Admina)
                    'roles' => $request->user()->roles->pluck('name')->toArray(),
                ] : null,
            ],
            'addToBasketProducts' => ProductControllerPages::getAllProducts(),
            'basket' => BasketFacade::getCurrent(),
            'menuCategories' => $this->getMenuCategories(),
            'dashboardModules' => $this->getDashboardModules($request)
        ]);
    }

    /**
     * Získání kategorií pro menu
     */
    private function getMenuCategories(): array
    {
        return [
            [
                'id' => 1,
                'name' => 'Sezóna',
                'slug' => 'sezona',
                'hasDropdown' => false,
            ],
            [
                'id' => 2,
                'name' => 'Mám potíže s',
                'slug' => 'mam-potize-s',
                'hasDropdown' => true,
                'subcategories' => [
                    [
                        'id' => 1,
                        'name' => 'BOLEST',
                        'slug' => 'bolest',
                        'icon' => 'https://placehold.co/40/ef4444/ffffff?text=💊',
                        'items' => [
                            'Tablety',
                            'Rozpustné formy',
                            'Masti, krémy, gely',
                            'Náplasti, spreje',
                            'Bylinné a masážní přípravky',
                        ],
                    ],
                    [
                        'id' => 2,
                        'name' => 'BOLEST V KRKU',
                        'slug' => 'bolest-v-krku',
                        'icon' => 'https://placehold.co/40/f59e0b/ffffff?text=🫁',
                        'items' => [
                            'Pastilky',
                            'Spreje a kloktadla',
                            'Bylinné pastilky',
                        ],
                    ],
                    [
                        'id' => 3,
                        'name' => 'CHŘIPKA, NACHLAZENÍ',
                        'slug' => 'chripka-nachlazeni',
                        'icon' => 'https://placehold.co/40/3b82f6/ffffff?text=🤧',
                        'items' => [
                            'Horečka a bolest',
                            'Podpora imunity',
                            'Horečka',
                        ],
                    ],
                    [
                        'id' => 4,
                        'name' => 'POHYB, KLOUBY',
                        'slug' => 'pohyb-klouby',
                        'icon' => 'https://placehold.co/40/10b981/ffffff?text=🦴',
                        'items' => [
                            'Masti, krémy, gely',
                            'Náplasti, spreje',
                            'Bylinné a masážní přípravky',
                            'Uvolnění svalů',
                            'Neuropatie',
                            '+ 4 dalších',
                        ],
                    ],
                    [
                        'id' => 5,
                        'name' => 'RÝMA',
                        'slug' => 'ryma',
                        'icon' => 'https://placehold.co/40/06b6d4/ffffff?text=👃',
                        'items' => [
                            'Nosní kapky a spreje',
                            'Olejové nosní kapky',
                            'Nosní masti',
                            'Alergická rýma',
                            'Mořské a sladkovodní vody',
                            '+ 3 dalších',
                        ],
                    ],
                    [
                        'id' => 6,
                        'name' => 'OČI',
                        'slug' => 'oci',
                        'icon' => 'https://placehold.co/40/8b5cf6/ffffff?text=👁️',
                        'items' => [
                            'Zánět',
                            'Alergie',
                            'Suché, podrážděné, slzící oko',
                            'Výživa očí',
                            'Péče o kontaktní čočky',
                        ],
                    ],
                    [
                        'id' => 7,
                        'name' => 'KAŠEL',
                        'slug' => 'kasal',
                        'icon' => 'https://placehold.co/40/f97316/ffffff?text=🫁',
                        'items' => [
                            'Suchý kašel',
                            'Vlhký kašel',
                            'Bylinné přípravky při kašli',
                            'Prsní masti',
                        ],
                    ],
                    [
                        'id' => 8,
                        'name' => 'UŠI',
                        'slug' => 'usi',
                        'icon' => 'https://placehold.co/40/ec4899/ffffff?text=👂',
                        'items' => [
                            'Bolest ucha',
                            'Ušní hygiena',
                            'Chrániče sluchu',
                        ],
                    ],
                ],
            ],
            [
                'id' => 3,
                'name' => 'Péče o pleť, vlasy, tělo',
                'slug' => 'pece-o-plet-vlasy-telo',
                'hasDropdown' => false,
            ],
            [
                'id' => 4,
                'name' => 'Zuby, ústa, rty',
                'slug' => 'zuby-usta-rty',
                'hasDropdown' => false,
            ],
            [
                'id' => 5,
                'name' => 'Pro maminky a děti',
                'slug' => 'pro-maminky-a-deti',
                'hasDropdown' => false,
            ],
            [
                'id' => 6,
                'name' => 'Výživa, čaje',
                'slug' => 'vyziva-caje',
                'hasDropdown' => false,
            ],
            [
                'id' => 7,
                'name' => 'Zdravotnické potřeby, první pomoc',
                'slug' => 'zdravotnicke-potreby-prvni-pomoc',
                'hasDropdown' => false,
            ],
            [
                'id' => 8,
                'name' => 'Pro zvířata',
                'slug' => 'pro-zvirata',
                'hasDropdown' => false,
            ],
        ];
    }

    /**
     * Získání modulů dashboardu pro sdílení do všech stránek
     * Filtruje moduly podle přístupů přihlášeného uživatele
     */
    private function getDashboardModules(Request $request): array
    {
        try {
            $user = $request->user();

            // Pokud není uživatel přihlášen, vrátit prázdné pole
            if (!$user) {
                return [];
            }

            // Získat ID rolí uživatele
            $roleIds = $user->roles()->pluck('roles.id')->toArray();

            // Získat ID modulů, ke kterým má uživatel přístup přes pivot module_role
            $userModuleIds = \DB::table('module_role')
                ->whereIn('role_id', $roleIds)
                ->pluck('module_id')
                ->unique()
                ->toArray();

            // Pokud uživatel nemá přístup k žádným modulům, vrátit prázdné pole
            if (empty($userModuleIds)) {
                return [];
            }

            // Získat všechny aktivní moduly
            $allModules = Module::tree()
                ->where('active', true)
                ->orderBy('rank')
                ->orderBy('order')
                ->get()
                ->toTree();

            // Filtrovat moduly - zobrazit pouze ty, ke kterým má uživatel přístup
            // a také jejich rodiče (pro navigaci)
            $filteredModules = $this->filterModulesByUserAccess($allModules, $userModuleIds);

            // Formátovat pro Inertia
            return $filteredModules->values()->map(function ($module) {
                return Module::formatModuleForInertia($module);
            })->toArray();
        } catch (\Exception $e) {
            // V případě chyby (např. při migraci) vrátíme prázdné pole
            // Logovat chybu pro debugging
            \Log::error('Error loading dashboard modules: ' . $e->getMessage());
            return [];
        }
    }

    /**
     * Filtruje moduly podle přístupů uživatele
     * Zobrazí modul, pokud:
     * - Uživatel má přímý přístup k modulu
     * - Uživatel má přístup k některému z potomků modulu (zobrazí se rodič pro navigaci)
     */
    private function filterModulesByUserAccess($modules, $userModuleIds)
    {
        $filtered = collect();

        foreach ($modules as $module) {
            // Zkontrolovat, zda má uživatel přístup k tomuto modulu
            $hasDirectAccess = in_array($module->id, $userModuleIds);

            // Zkontrolovat, zda má uživatel přístup k některému z potomků
            $filteredChildren = collect();
            if ($module->children && $module->children->isNotEmpty()) {
                $filteredChildren = $this->filterModulesByUserAccess($module->children, $userModuleIds);
            }

            $hasChildAccess = $filteredChildren->isNotEmpty();

            // Zobrazit modul, pokud má uživatel přímý přístup nebo přístup k potomkům
            if ($hasDirectAccess || $hasChildAccess) {
                // Vytvořit kopii modulu s filtrovanými dětmi
                $filteredModule = clone $module;
                if ($filteredChildren->isNotEmpty()) {
                    $filteredModule->setRelation('children', $filteredChildren);
                } else {
                    $filteredModule->setRelation('children', collect());
                }
                $filtered->push($filteredModule);
            }
        }

        return $filtered;
    }
}
