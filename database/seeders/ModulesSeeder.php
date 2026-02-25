<?php

namespace Database\Seeders;

use App\Models\Module;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;

class ModulesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Dočasně vypnout kontrolu cizích klíčů kvůli pivot tabulce module_role
        Schema::disableForeignKeyConstraints();

        // Vyčistit pivot tabulku role -> modul, aby nebránila truncate na modules
        \DB::table('module_role')->truncate();

        // Vyčištění stávajících modulů
        Module::truncate();

        Schema::enableForeignKeyConstraints();

        // ===== HLAVNÍ SEKCE 1: E-SHOP =====
        $eshop = Module::create([
            'name' => 'E-shop',
            'slug' => 'e-shop',
            'description' => 'Správa e-shopu',
            'icon' => '<svg fill="currentColor" viewBox="0 0 16 16"><path d="M0 2.5A.5.5 0 0 1 .5 2H2a.5.5 0 0 1 .485.379L2.89 4H14.5a.5.5 0 0 1 .491.592l-1.5 8A.5.5 0 0 1 13 13H4a.5.5 0 0 1-.491-.408L2.01 3.607 1.61 2H.5a.5.5 0 0 1-.5-.5zM5 12a2 2 0 1 0 0 4 2 2 0 0 0 0-4zm7 0a2 2 0 1 0 0 4 2 2 0 0 0 0-4zm-7 1a1 1 0 1 1 0 2 1 1 0 0 1 0-2zm7 0a1 1 0 1 1 0 2 1 1 0 0 1 0-2z"/></svg>',
            'route' => null,
            'active' => true,
            'order' => 1,
        ]);

        // E-shop -> Přehled
        Module::create([
            'parent_id' => $eshop->id,
            'name' => 'Přehled',
            'slug' => 'e-shop-overview',
            'description' => 'Přehled e-shopu',
            'icon' => '<svg fill="currentColor" viewBox="0 0 16 16"><path d="M6.5 14.5v-3.505c0-.245.25-.495.5-.495h2c.25 0 .5.25.5.5v3.5a.5.5 0 0 0 .5.5h4a.5.5 0 0 0 .5-.5v-7a.5.5 0 0 0-.146-.354L13 5.793V2.5a.5.5 0 0 0-.5-.5h-1a.5.5 0 0 0-.5.5v1.293L8.354 1.146a.5.5 0 0 0-.708 0l-6 6A.5.5 0 0 0 1.5 7.5v7a.5.5 0 0 0 .5.5h4a.5.5 0 0 0 .5-.5Z"/></svg>',
            'route' => 'dashboard.index',
            'active' => true,
            'order' => 1,
        ]);

        // E-shop -> Objednávky
        $orders = Module::create([
            'parent_id' => $eshop->id,
            'name' => 'Objednávky',
            'slug' => 'orders',
            'description' => 'Správa objednávek',
            'icon' => '<svg fill="currentColor" viewBox="0 0 16 16"><path d="M5 0a.5.5 0 0 1 .5.5V2h1V.5a.5.5 0 0 1 1 0V2h1V.5a.5.5 0 0 1 1 0V2h1V.5a.5.5 0 0 1 1 0V2A2.5 2.5 0 0 1 14 4.5h1.5a.5.5 0 0 1 0 1H14v1h1.5a.5.5 0 0 1 0 1H14v1h1.5a.5.5 0 0 1 0 1H14v1h1.5a.5.5 0 0 1 0 1H14a2.5 2.5 0 0 1-2.5 2.5v1.5a.5.5 0 0 1-1 0V14h-1v1.5a.5.5 0 0 1-1 0V14h-1v1.5a.5.5 0 0 1-1 0V14h-1v1.5a.5.5 0 0 1-1 0V14A2.5 2.5 0 0 1 2 11.5H.5a.5.5 0 0 1 0-1H2v-1H.5a.5.5 0 0 1 0-1H2v-1H.5a.5.5 0 0 1 0-1H2v-1H.5a.5.5 0 0 1 0-1H2A2.5 2.5 0 0 1 4.5 2V.5A.5.5 0 0 1 5 0z"/></svg>',
            'route' => null,
            'active' => true,
            'order' => 2,
        ]);

        Module::create([
            'parent_id' => $orders->id,
            'name' => 'Seznam objednávky',
            'slug' => 'orders-list',
            'description' => 'Seznam všech objednávek',
            'icon' => '<svg fill="currentColor" viewBox="0 0 16 16"><path d="M12 0H4a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2zM5 4h6a.5.5 0 0 1 0 1H5a.5.5 0 0 1 0-1zm-.5 2.5A.5.5 0 0 1 5 6h6a.5.5 0 0 1 0 1H5a.5.5 0 0 1-.5-.5zM5 8h6a.5.5 0 0 1 0 1H5a.5.5 0 0 1 0-1zm0 2h3a.5.5 0 0 1 0 1H5a.5.5 0 0 1 0-1z"/></svg>',
            'route' => 'objednavky.index',
            'active' => true,
            'order' => 1,
        ]);

        // E-shop -> Produkty
        $products = Module::create([
            'parent_id' => $eshop->id,
            'name' => 'Produkty',
            'slug' => 'products',
            'description' => 'Správa produktů',
            'icon' => '<svg fill="currentColor" viewBox="0 0 16 16"><path d="M0 2.5A.5.5 0 0 1 .5 2H2a.5.5 0 0 1 .485.379L2.89 4H14.5a.5.5 0 0 1 .485.621l-1.5 6A.5.5 0 0 1 13 11H4a.5.5 0 0 1-.485-.379L1.61 3H.5a.5.5 0 0 1-.5-.5zM3.14 5l1.25 5h8.22l1.25-5H3.14zM5 13a1 1 0 1 0 0 2 1 1 0 0 0 0-2zm-2 1a2 2 0 1 1 4 0 2 2 0 0 1-4 0zm9-1a1 1 0 1 0 0 2 1 1 0 0 0 0-2zm-2 1a2 2 0 1 1 4 0 2 2 0 0 1-4 0z"/></svg>',
            'route' => null,
            'active' => true,
            'order' => 3,
        ]);

        Module::create([
            'parent_id' => $products->id,
            'name' => 'Přehled',
            'slug' => 'products-overview',
            'description' => 'Přehled produktů',
            'icon' => '<svg fill="currentColor" viewBox="0 0 16 16"><path d="M1 2.5A1.5 1.5 0 0 1 2.5 1h3A1.5 1.5 0 0 1 7 2.5v3A1.5 1.5 0 0 1 5.5 7h-3A1.5 1.5 0 0 1 1 5.5v-3zm8 0A1.5 1.5 0 0 1 10.5 1h3A1.5 1.5 0 0 1 15 2.5v3A1.5 1.5 0 0 1 13.5 7h-3A1.5 1.5 0 0 1 9 5.5v-3zm-8 8A1.5 1.5 0 0 1 2.5 9h3A1.5 1.5 0 0 1 7 10.5v3A1.5 1.5 0 0 1 5.5 15h-3A1.5 1.5 0 0 1 1 13.5v-3zm8 0A1.5 1.5 0 0 1 10.5 9h3a1.5 1.5 0 0 1 1.5 1.5v3a1.5 1.5 0 0 1-1.5 1.5h-3A1.5 1.5 0 0 1 9 13.5v-3z"/></svg>',
            'route' => 'produkty.index',
            'active' => true,
            'order' => 1,
        ]);

        Module::create([
            'parent_id' => $products->id,
            'name' => 'Marže',
            'slug' => 'products-margin',
            'description' => 'Správa marží produktů',
            'icon' => '<svg fill="currentColor" viewBox="0 0 16 16"><path d="M4 10.781c.148 1.667 1.513 2.85 3.591 3.003V15h1.043v-1.216c2.27-.179 3.678-1.438 3.678-3.3 0-1.59-.947-2.51-2.956-3.028l-.722-.187V3.467c1.122.11 1.879.714 2.07 1.616h1.47c-.166-1.6-1.54-2.748-3.54-2.875V1H7.591v1.233c-1.939.23-3.27 1.472-3.27 3.156 0 1.454.966 2.483 2.661 2.917l.61.162v4.031c-1.149-.17-1.94-.8-2.131-1.718H4zm3.391-3.836c-1.043-.263-1.6-.825-1.6-1.616 0-.944.704-1.641 1.8-1.828v3.495l-.2-.05zm1.591 1.872c1.287.323 1.852.859 1.852 1.769 0 1.097-.826 1.828-2.2 1.939V8.73l.348.086z"/></svg>',
            'route' => 'produkty.marze',
            'active' => true,
            'order' => 2,
        ]);

        Module::create([
            'parent_id' => $products->id,
            'name' => 'Kategorie',
            'slug' => 'products-categories',
            'description' => 'Správa kategorií produktů',
            'icon' => '<svg fill="currentColor" viewBox="0 0 16 16"><path d="M1 2.5A1.5 1.5 0 0 1 2.5 1h3A1.5 1.5 0 0 1 7 2.5v3A1.5 1.5 0 0 1 5.5 7h-3A1.5 1.5 0 0 1 1 5.5v-3zm8 0A1.5 1.5 0 0 1 10.5 1h3A1.5 1.5 0 0 1 15 2.5v3A1.5 1.5 0 0 1 13.5 7h-3A1.5 1.5 0 0 1 9 5.5v-3zm-8 8A1.5 1.5 0 0 1 2.5 9h3A1.5 1.5 0 0 1 7 10.5v3A1.5 1.5 0 0 1 5.5 15h-3A1.5 1.5 0 0 1 1 13.5v-3zm8 0A1.5 1.5 0 0 1 10.5 9h3a1.5 1.5 0 0 1 1.5 1.5v3a1.5 1.5 0 0 1-1.5 1.5h-3A1.5 1.5 0 0 1 9 13.5v-3z"/></svg>',
            'route' => 'produkty.kategorie',
            'active' => true,
            'order' => 3,
        ]);

        Module::create([
            'parent_id' => $products->id,
            'name' => 'Značky',
            'slug' => 'products-brands',
            'description' => 'Správa značek produktů',
            'icon' => '<svg fill="currentColor" viewBox="0 0 16 16"><path d="M2 2a2 2 0 0 1 2-2h4.5a.5.5 0 0 1 0 1H4a1 1 0 0 0-1 1v12a1 1 0 0 0 1 1h8a1 1 0 0 0 1-1V4.5a.5.5 0 0 1 1 0V14a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V2z"/><path d="M9.5 3a.5.5 0 0 0 0-1H7a.5.5 0 0 0 0 1h2.5zM9 5.5a.5.5 0 0 0-.5-.5h-2a.5.5 0 0 0 0 1h2a.5.5 0 0 0 .5-.5zm0 2a.5.5 0 0 0-.5-.5h-2a.5.5 0 0 0 0 1h2a.5.5 0 0 0 .5-.5z"/></svg>',
            'route' => 'produkty.znacky',
            'active' => true,
            'order' => 4,
        ]);

        Module::create([
            'parent_id' => $products->id,
            'name' => 'Distribuce',
            'slug' => 'products-distribution',
            'description' => 'Správa distribuce produktů',
            'icon' => '<svg fill="currentColor" viewBox="0 0 16 16"><path d="M14 1a1 1 0 0 1 1 1v8a1 1 0 0 1-1 1H4.414A2 2 0 0 0 3 11.586l-2 2V2a1 1 0 0 1 1-1h12zM2 0a2 2 0 0 0-2 2v12.793a.5.5 0 0 0 .854.353l2.853-2.853A1 1 0 0 1 4.414 12H14a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2H2z"/></svg>',
            'route' => 'produkty.distribuce',
            'active' => true,
            'order' => 5,
        ]);

        // E-shop -> Bonusy
        Module::create([
            'parent_id' => $eshop->id,
            'name' => 'Bonusy',
            'slug' => 'bonuses',
            'description' => 'Správa bonusového programu',
            'icon' => '<svg fill="currentColor" viewBox="0 0 16 16"><path d="M3.612 15.443c-.386.198-.824-.149-.746-.592l.83-4.73L.173 6.765c-.329-.314-.158-.888.283-.95l4.898-.696L7.538.792c.197-.39.73-.39.927 0l2.184 4.327 4.898.696c.441.062.612.636.282.95l-3.522 3.356.83 4.73c.078.443-.36.79-.746.592L8 13.187l-4.389 2.256z"/></svg>',
            'route' => 'bonusy.index',
            'active' => true,
            'order' => 4,
        ]);

        // E-shop -> E-recept
        Module::create([
            'parent_id' => $eshop->id,
            'name' => 'E-recept',
            'slug' => 'e-prescription',
            'description' => 'Správa elektronických receptů',
            'icon' => '<svg fill="currentColor" viewBox="0 0 16 16"><path d="M4 0a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2H4zm0 1h8a1 1 0 0 1 1 1v12a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1z"/><path d="M5.5 3a.5.5 0 0 0 0 1h5a.5.5 0 0 0 0-1h-5zm0 2a.5.5 0 0 0 0 1h5a.5.5 0 0 0 0-1h-5zm0 2a.5.5 0 0 0 0 1h5a.5.5 0 0 0 0-1h-5zm0 2a.5.5 0 0 0 0 1h5a.5.5 0 0 0 0-1h-5z"/></svg>',
            'route' => 'e-recept.index',
            'active' => true,
            'order' => 5,
        ]);

        // E-shop -> Zákazníci
        Module::create([
            'parent_id' => $eshop->id,
            'name' => 'Zákazníci',
            'slug' => 'customers',
            'description' => 'Správa zákazníků',
            'icon' => '<svg fill="currentColor" viewBox="0 0 16 16"><path d="M7 14s-1 0-1-1 1-4 5-4 5 3 5 4-1 1-1 1H7zm4-6a3 3 0 1 0 0-6 3 3 0 0 0 0 6z"/><path fill-rule="evenodd" d="M5.216 14A2.238 2.238 0 0 1 5 13c0-1.355.68-2.75 1.936-3.72A6.325 6.325 0 0 0 5 9c-4 0-5 3-5 4s1 1 1 1h4.216z"/><path d="M4.5 8a2.5 2.5 0 1 0 0-5 2.5 2.5 0 0 0 0 5z"/></svg>',
            'route' => 'zakaznici.index',
            'active' => true,
            'order' => 6,
        ]);

        // ===== HLAVNÍ SEKCE 2: OBSAH =====
        $content = Module::create([
            'name' => 'Obsah',
            'slug' => 'content',
            'description' => 'Správa obsahu webu',
            'icon' => '<svg fill="currentColor" viewBox="0 0 16 16"><path d="M5 4a.5.5 0 0 0 0 1h6a.5.5 0 0 0 0-1H5zm-.5 2.5A.5.5 0 0 1 5 6h6a.5.5 0 0 1 0 1H5a.5.5 0 0 1-.5-.5zM5 8a.5.5 0 0 0 0 1h6a.5.5 0 0 0 0-1H5zm0 2a.5.5 0 0 0 0 1h3a.5.5 0 0 0 0-1H5z"/><path d="M2 2a2 2 0 0 1 2-2h8a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V2zm10-1H4a1 1 0 0 0-1 1v12a1 1 0 0 0 1 1h8a1 1 0 0 0 1-1V2a1 1 0 0 0-1-1z"/></svg>',
            'route' => null,
            'active' => true,
            'order' => 2,
        ]);

        Module::create([
            'parent_id' => $content->id,
            'name' => 'Bannery',
            'slug' => 'content-banners',
            'description' => 'Správa bannerů',
            'icon' => '<svg fill="currentColor" viewBox="0 0 16 16"><path d="M6.002 5.5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0z"/><path d="M1.5 2A1.5 1.5 0 0 0 0 3.5v9A1.5 1.5 0 0 0 1.5 14h13a1.5 1.5 0 0 0 1.5-1.5v-9A1.5 1.5 0 0 0 14.5 2h-13zm13 1a.5.5 0 0 1 .5.5v6l-3.775-1.947a.5.5 0 0 0-.577.093l-3.71 3.71-2.66-1.772a.5.5 0 0 0-.63.062L1.002 12v.54A.505.505 0 0 1 1 12.5v-9a.5.5 0 0 1 .5-.5h13z"/></svg>',
            'route' => 'obecne.bannery',
            'active' => true,
            'order' => 1,
        ]);

        Module::create([
            'parent_id' => $content->id,
            'name' => 'Stránky',
            'slug' => 'content-pages',
            'description' => 'Správa statických stránek',
            'icon' => '<svg fill="currentColor" viewBox="0 0 16 16"><path d="M5 4a.5.5 0 0 0 0 1h6a.5.5 0 0 0 0-1H5zm-.5 2.5A.5.5 0 0 1 5 6h6a.5.5 0 0 1 0 1H5a.5.5 0 0 1-.5-.5zM5 8a.5.5 0 0 0 0 1h6a.5.5 0 0 0 0-1H5zm0 2a.5.5 0 0 0 0 1h3a.5.5 0 0 0 0-1H5z"/><path d="M2 2a2 2 0 0 1 2-2h8a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V2zm10-1H4a1 1 0 0 0-1 1v12a1 1 0 0 0 1 1h8a1 1 0 0 0 1-1V2a1 1 0 0 0-1-1z"/></svg>',
            'route' => 'obecne.stranky',
            'active' => true,
            'order' => 2,
        ]);

        Module::create([
            'parent_id' => $content->id,
            'name' => 'Články',
            'slug' => 'content-articles',
            'description' => 'Správa článků blogu',
            'icon' => '<svg fill="currentColor" viewBox="0 0 16 16"><path d="M14 4.5V14a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V2a2 2 0 0 1 2-2h5.5L14 4.5zm-3 0A1.5 1.5 0 0 1 9.5 3V1H4a1 1 0 0 0-1 1v12a1 1 0 0 0 1 1h8a1 1 0 0 0 1-1V4.5h-2z"/></svg>',
            'route' => 'obecne.clanky',
            'active' => true,
            'order' => 3,
        ]);

        Module::create([
            'parent_id' => $content->id,
            'name' => 'Obchodní podmínky',
            'slug' => 'content-terms',
            'description' => 'Správa obchodních podmínek',
            'icon' => '<svg fill="currentColor" viewBox="0 0 16 16"><path d="M14 4.5V14a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V2a2 2 0 0 1 2-2h5.5L14 4.5zm-3 0A1.5 1.5 0 0 1 9.5 3V1H4a1 1 0 0 0-1 1v12a1 1 0 0 0 1 1h8a1 1 0 0 0 1-1V4.5h-2z"/></svg>',
            'route' => 'obecne.obchodni-podminky',
            'active' => true,
            'order' => 4,
        ]);

        Module::create([
            'parent_id' => $content->id,
            'name' => 'Košíky (nedokončené)',
            'slug' => 'content-abandoned-carts',
            'description' => 'Správa nedokončených košíků',
            'icon' => '<svg fill="currentColor" viewBox="0 0 16 16"><path d="M0 1.5A.5.5 0 0 1 .5 1H2a.5.5 0 0 1 .485.379L2.89 3H14.5a.5.5 0 0 1 .49.598l-1 5a.5.5 0 0 1-.465.401l-9.397.472L4.415 11H13a.5.5 0 0 1 0 1H4a.5.5 0 0 1-.491-.408L2.01 3.607 1.61 2H.5a.5.5 0 0 1-.5-.5zM3.102 4l.84 4.479 9.144-.459L13.89 4H3.102zM5 12a2 2 0 1 0 0 4 2 2 0 0 0 0-4zm7 0a2 2 0 1 0 0 4 2 2 0 0 0 0-4zm-7 1a1 1 0 1 1 0 2 1 1 0 0 1 0-2zm7 0a1 1 0 1 1 0 2 1 1 0 0 1 0-2z"/></svg>',
            'route' => 'obecne.kosiky-nedokoncene',
            'active' => true,
            'order' => 5,
        ]);

        Module::create([
            'parent_id' => $content->id,
            'name' => 'Soubory',
            'slug' => 'content-files',
            'description' => 'Správa souborů a médií',
            'icon' => '<svg fill="currentColor" viewBox="0 0 16 16"><path d="M9.828 3h3.982a2 2 0 0 1 1.992 2.181l-.637 7A2 2 0 0 1 13.174 14H2.825a2 2 0 0 1-1.991-1.819l-.637-7a1.99 1.99 0 0 1 .342-1.31L.5 3a2 2 0 0 1 2-2h3.672a2 2 0 0 1 1.414.586l.828.828A2 2 0 0 0 9.828 3zm-8.322.12C1.72 3.042 1.95 3 2.19 3h5.396l-.707-.707A1 1 0 0 0 6.172 2H2.5a1 1 0 0 0-1 .981l.006.139z"/></svg>',
            'route' => 'soubory.index',
            'active' => true,
            'order' => 6,
        ]);

        // ===== HLAVNÍ SEKCE 3: NASTAVENÍ =====
        $settings = Module::create([
            'name' => 'Nastavení',
            'slug' => 'settings',
            'description' => 'Nastavení systému',
            'icon' => '<svg fill="currentColor" viewBox="0 0 16 16"><path d="M9.796 1.343c-.527-1.79-3.065-1.79-3.592 0l-.094.319a.873.873 0 0 1-1.255.52l-.292-.16c-1.64-.892-3.433.902-2.54 2.541l.159.292a.873.873 0 0 1-.52 1.255l-.319.094c-1.79.527-1.79 3.065 0 3.592l.319.094a.873.873 0 0 1 .52 1.255l-.16.292c-.892 1.64.901 3.434 2.541 2.54l.292-.159a.873.873 0 0 1 1.255.52l.094.319c.527 1.79 3.065 1.79 3.592 0l.094-.319a.873.873 0 0 1 1.255-.52l.292.16c1.64.893 3.434-.902 2.54-2.541l-.159-.292a.873.873 0 0 1 .52-1.255l.319-.094c1.79-.527 1.79-3.065 0-3.592l-.319-.094a.873.873 0 0 1-.52-1.255l.16-.292c.893-1.64-.902-3.433-2.541-2.54l-.292.159a.873.873 0 0 1-1.255-.52l-.094-.319z"/><path d="M8 5.754a2.246 2.246 0 1 0 0 4.492 2.246 2.246 0 0 0 0-4.492z"/></svg>',
            'route' => null,
            'active' => true,
            'order' => 3,
        ]);

        Module::create([
            'parent_id' => $settings->id,
            'name' => 'Aktualizace',
            'slug' => 'settings-updates',
            'description' => 'Správa aktualizací systému',
            'icon' => '<svg fill="currentColor" viewBox="0 0 16 16"><path d="M11.534 7h3.932a.25.25 0 0 1 .192.41l-1.966 2.36a.25.25 0 0 1-.384 0l-1.966-2.36a.25.25 0 0 1 .192-.41zm-11 2h3.932a.25.25 0 0 0 .192-.41L2.692 6.23a.25.25 0 0 0-.384 0L.342 8.59A.25.25 0 0 0 .534 9z"/><path fill-rule="evenodd" d="M8 3c-1.552 0-2.94.707-3.857 1.818a.5.5 0 1 1-.771-.636A6.002 6.002 0 0 1 13.917 7H12.9A5.002 5.002 0 0 0 8 3zM3.1 9a5.002 5.002 0 0 0 8.757 2.182.5.5 0 1 1 .771.636A6.002 6.002 0 0 1 2.083 9H3.1z"/></svg>',
            'route' => 'nastaveni.aktualizace',
            'active' => true,
            'order' => 1,
        ]);

        Module::create([
            'parent_id' => $settings->id,
            'name' => 'Konstanty',
            'slug' => 'settings-constants',
            'description' => 'Správa systémových konstant',
            'icon' => '<svg fill="currentColor" viewBox="0 0 16 16"><path d="M8 4.754a3.246 3.246 0 1 0 0 6.492 3.246 3.246 0 0 0 0-6.492zM5.754 8a2.246 2.246 0 1 1 4.492 0 2.246 2.246 0 0 1-4.492 0z"/></svg>',
            'route' => 'nastaveni.konstanty',
            'active' => true,
            'order' => 2,
        ]);

        Module::create([
            'parent_id' => $settings->id,
            'name' => 'Dopravy',
            'slug' => 'settings-transports',
            'description' => 'Správa dopravních metod',
            'icon' => '<svg fill="currentColor" viewBox="0 0 16 16"><path d="M0 3.5A1.5 1.5 0 0 1 1.5 2h9A1.5 1.5 0 0 1 12 3.5V5h1.02a1.5 1.5 0 0 1 1.17.563l1.481 1.85a1.5 1.5 0 0 1 .329.938V10.5a1.5 1.5 0 0 1-1.5 1.5H14a2 2 0 1 1-4 0H5a2 2 0 1 1-3.998-.085A1.5 1.5 0 0 1 0 10.5v-7zm1.294 7.456A1.999 1.999 0 0 1 4.732 11h5.536a2.01 2.01 0 0 1 .732-.732V3.5a.5.5 0 0 0-.5-.5h-9a.5.5 0 0 0-.5.5v7a.5.5 0 0 0 .294.456zM12 10a2 2 0 0 1 1.732 1h.768a.5.5 0 0 0 .5-.5V8.35a.5.5 0 0 0-.11-.312l-1.48-1.85A.5.5 0 0 0 13.02 6H12v4zm-9 1a1 1 0 1 0 0 2 1 1 0 0 0 0-2zm9 0a1 1 0 1 0 0 2 1 1 0 0 0 0-2z"/></svg>',
            'route' => 'nastaveni.dopravy',
            'active' => true,
            'order' => 3,
        ]);

        Module::create([
            'parent_id' => $settings->id,
            'name' => 'Platby',
            'slug' => 'settings-payments',
            'description' => 'Správa platebních metod',
            'icon' => '<svg fill="currentColor" viewBox="0 0 16 16"><path d="M0 4a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v8a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V4zm2-1a1 1 0 0 0-1 1v1h14V4a1 1 0 0 0-1-1H2zm13 4H1v5a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1V7z"/><path d="M2 10a1 1 0 0 1 1-1h1a1 1 0 0 1 1 1v1a1 1 0 0 1-1 1H3a1 1 0 0 1-1-1v-1z"/></svg>',
            'route' => 'nastaveni.platby',
            'active' => true,
            'order' => 4,
        ]);

        Module::create([
            'parent_id' => $settings->id,
            'name' => 'Pobočky',
            'slug' => 'settings-branches',
            'description' => 'Správa poboček',
            'icon' => '<svg fill="currentColor" viewBox="0 0 16 16"><path d="M8.707 1.5a1 1 0 0 0-1.414 0L.646 8.146a.5.5 0 0 0 .708.708L8 2.207l6.646 6.647a.5.5 0 0 0 .708-.708L13 5.793V2.5a.5.5 0 0 0-.5-.5h-1a.5.5 0 0 0-.5.5v1.293L8.707 1.5Z"/><path d="m8 3.293 6 6V13.5a1.5 1.5 0 0 1-1.5 1.5h-9A1.5 1.5 0 0 1 2 13.5V9.293l6-6Z"/></svg>',
            'route' => 'nastaveni.pobocky',
            'active' => true,
            'order' => 5,
        ]);

        Module::create([
            'parent_id' => $settings->id,
            'name' => 'E-maily (texty)',
            'slug' => 'settings-emails',
            'description' => 'Správa e-mailových šablon',
            'icon' => '<svg fill="currentColor" viewBox="0 0 16 16"><path d="M0 4a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v8a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V4Zm2-1a1 1 0 0 0-1 1v.217l7 4.2 7-4.2V4a1 1 0 0 0-1-1H2Zm13 2.383-4.708 2.825L15 11.105V5.383Zm-.034 6.876-5.64-3.471L8 9.583l-1.326-.795-5.64 3.47A1 1 0 0 0 2 13h12a1 1 0 0 0 .966-.741ZM1 11.105l4.708-2.897L1 5.383v5.722Z"/></svg>',
            'route' => 'nastaveni.emaily',
            'active' => true,
            'order' => 6,
        ]);

        // Nastavení -> Uživatelé
        $users = Module::create([
            'parent_id' => $settings->id,
            'name' => 'Uživatelé',
            'slug' => 'users',
            'description' => 'Správa uživatelů systému',
            'icon' => '<svg fill="currentColor" viewBox="0 0 16 16"><path d="M15 14s1 0 1-1-1-4-5-4-5 3-5 4 1 1 1 1h8zm-7.978-1A.261.261 0 0 1 7 12.996c.001-.264.167-1.03.76-1.72C8.312 10.629 9.282 10 11 10c1.717 0 2.687.63 3.24 1.276.593.69.758 1.457.76 1.72l-.008.002a.274.274 0 0 1-.014.002H7.022zM11 7a2 2 0 1 0 0-4 2 2 0 0 0 0 4zm3-2a3 3 0 1 1-6 0 3 3 0 0 1 6 0zM6.936 9.28a5.88 5.88 0 0 0-1.23-.247A7.35 7.35 0 0 0 5 9c-4 0-5 3-5 4 0 .667.333 1 1 1h4.216A2.238 2.238 0 0 1 5 13c0-1.01.377-2.042 1.09-2.904.243-.294.526-.569.846-.816zM4.92 10A5.493 5.493 0 0 0 4 13H1c0-.26.164-1.03.76-1.724.545-.636 1.492-1.256 3.16-1.275zM1.5 5.5a3 3 0 1 1 6 0 3 3 0 0 1-6 0zm3-2a2 2 0 1 0 0 4 2 2 0 0 0 0-4z"/></svg>',
            'route' => null,
            'active' => true,
            'order' => 7,
        ]);

        Module::create([
            'parent_id' => $users->id,
            'name' => 'Seznam',
            'slug' => 'users-list',
            'description' => 'Seznam všech uživatelů',
            'icon' => '<svg fill="currentColor" viewBox="0 0 16 16"><path d="M12 0H4a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2zM5 4h6a.5.5 0 0 1 0 1H5a.5.5 0 0 1 0-1zm-.5 2.5A.5.5 0 0 1 5 6h6a.5.5 0 0 1 0 1H5a.5.5 0 0 1-.5-.5zM5 8h6a.5.5 0 0 1 0 1H5a.5.5 0 0 1 0-1zm0 2h3a.5.5 0 0 1 0 1H5a.5.5 0 0 1 0-1z"/></svg>',
            'route' => 'uzivatele.index',
            'active' => true,
            'order' => 1,
        ]);

        Module::create([
            'parent_id' => $users->id,
            'name' => 'Role',
            'slug' => 'users-roles',
            'description' => 'Správa uživatelských rolí',
            'icon' => '<svg fill="currentColor" viewBox="0 0 16 16"><path d="M1 14s-1 0-1-1 1-4 6-4 6 3 6 4-1 1-1 1H1zm5-6a3 3 0 1 0 0-6 3 3 0 0 0 0 6z"/><path fill-rule="evenodd" d="M13.5 5a.5.5 0 0 1 .5.5V7h1.5a.5.5 0 0 1 0 1H14v1.5a.5.5 0 0 1-1 0V8h-1.5a.5.5 0 0 1 0-1H13V5.5a.5.5 0 0 1 .5-.5z"/></svg>',
            'route' => 'uzivatele.role',
            'active' => true,
            'order' => 2,
        ]);

        Module::create([
            'parent_id' => $users->id,
            'name' => 'Oprávnění',
            'slug' => 'users-permissions',
            'description' => 'Správa oprávnění',
            'icon' => '<svg fill="currentColor" viewBox="0 0 16 16"><path d="M8 1a2 2 0 0 1 2 2v4H6V3a2 2 0 0 1 2-2zm3 6V3a3 3 0 0 0-6 0v4a2 2 0 0 0-2 2v5a2 2 0 0 0 2 2h6a2 2 0 0 0 2-2V9a2 2 0 0 0-2-2z"/></svg>',
            'route' => 'uzivatele.opravneni',
            'active' => true,
            'order' => 3,
        ]);

        // Nastavení -> PharmData
        $pharmdata = Module::create([
            'parent_id' => $settings->id,
            'name' => 'PharmData',
            'slug' => 'pharmdata',
            'description' => 'Správa farmaceutických dat',
            'icon' => '<svg fill="currentColor" viewBox="0 0 16 16"><path d="M1.333 2.667C1.333 1.194 4.318 0 8 0s6.667 1.194 6.667 2.667V4c0 1.473-2.985 2.667-6.667 2.667S1.333 5.473 1.333 4V2.667z"/><path d="M1.333 6.334v3C1.333 10.805 4.318 12 8 12s6.667-1.194 6.667-2.667V6.334a6.51 6.51 0 0 1-1.458.79C11.81 7.684 9.967 8 8 8c-1.966 0-3.809-.317-5.208-.876a6.508 6.508 0 0 1-1.458-.79z"/><path d="M14.667 11.668a6.51 6.51 0 0 1-1.458.789c-1.4.56-3.242.876-5.21.876-1.966 0-3.809-.316-5.208-.876a6.51 6.51 0 0 1-1.458-.79v1.666C1.333 14.806 4.318 16 8 16s6.667-1.194 6.667-2.667v-1.665z"/></svg>',
            'route' => null,
            'active' => true,
            'order' => 8,
        ]);

        Module::create([
            'parent_id' => $pharmdata->id,
            'name' => 'ATC skupiny',
            'slug' => 'pharmdata-atc-groups',
            'description' => 'Správa ATC skupin',
            'icon' => '<svg fill="currentColor" viewBox="0 0 16 16"><path d="M1 2.5A1.5 1.5 0 0 1 2.5 1h3A1.5 1.5 0 0 1 7 2.5v3A1.5 1.5 0 0 1 5.5 7h-3A1.5 1.5 0 0 1 1 5.5v-3zm8 0A1.5 1.5 0 0 1 10.5 1h3A1.5 1.5 0 0 1 15 2.5v3A1.5 1.5 0 0 1 13.5 7h-3A1.5 1.5 0 0 1 9 5.5v-3zm-8 8A1.5 1.5 0 0 1 2.5 9h3A1.5 1.5 0 0 1 7 10.5v3A1.5 1.5 0 0 1 5.5 15h-3A1.5 1.5 0 0 1 1 13.5v-3zm8 0A1.5 1.5 0 0 1 10.5 9h3a1.5 1.5 0 0 1 1.5 1.5v3a1.5 1.5 0 0 1-1.5 1.5h-3A1.5 1.5 0 0 1 9 13.5v-3z"/></svg>',
            'route' => 'pharmdata.atc-skupiny',
            'active' => true,
            'order' => 1,
        ]);

        Module::create([
            'parent_id' => $pharmdata->id,
            'name' => 'Produkty',
            'slug' => 'pharmdata-products',
            'description' => 'Správa farmaceutických produktů',
            'icon' => '<svg fill="currentColor" viewBox="0 0 16 16"><path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"/><path d="M10.97 4.97a.235.235 0 0 0-.02.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-1.071-1.05z"/></svg>',
            'route' => 'pharmdata.produkty',
            'active' => true,
            'order' => 2,
        ]);

        Module::create([
            'parent_id' => $pharmdata->id,
            'name' => 'Kategorie',
            'slug' => 'pharmdata-categories',
            'description' => 'Správa kategorií farmaceutických produktů',
            'icon' => '<svg fill="currentColor" viewBox="0 0 16 16"><path d="M1 2.5A1.5 1.5 0 0 1 2.5 1h3A1.5 1.5 0 0 1 7 2.5v3A1.5 1.5 0 0 1 5.5 7h-3A1.5 1.5 0 0 1 1 5.5v-3zm8 0A1.5 1.5 0 0 1 10.5 1h3A1.5 1.5 0 0 1 15 2.5v3A1.5 1.5 0 0 1 13.5 7h-3A1.5 1.5 0 0 1 9 5.5v-3zm-8 8A1.5 1.5 0 0 1 2.5 9h3A1.5 1.5 0 0 1 7 10.5v3A1.5 1.5 0 0 1 5.5 15h-3A1.5 1.5 0 0 1 1 13.5v-3zm8 0A1.5 1.5 0 0 1 10.5 9h3a1.5 1.5 0 0 1 1.5 1.5v3a1.5 1.5 0 0 1-1.5 1.5h-3A1.5 1.5 0 0 1 9 13.5v-3z"/></svg>',
            'route' => 'pharmdata.kategorie',
            'active' => true,
            'order' => 3,
        ]);

        Module::create([
            'parent_id' => $pharmdata->id,
            'name' => 'Výrobci',
            'slug' => 'pharmdata-manufacturers',
            'description' => 'Správa výrobců farmaceutických produktů',
            'icon' => '<svg fill="currentColor" viewBox="0 0 16 16"><path d="M8 0a.5.5 0 0 1 .447.276L10.5 4h3a.5.5 0 0 1 .447.724l-6 12A.5.5 0 0 1 7 16a.5.5 0 0 1-.447-.276L4.5 12h-3a.5.5 0 0 1-.447-.724l6-12A.5.5 0 0 1 8 0z"/></svg>',
            'route' => 'pharmdata.vyrobci',
            'active' => true,
            'order' => 4,
        ]);

        Module::create([
            'parent_id' => $pharmdata->id,
            'name' => 'Volnoprodejnost',
            'slug' => 'pharmdata-otc',
            'description' => 'Správa volnoprodejnosti produktů',
            'icon' => '<svg fill="currentColor" viewBox="0 0 16 16"><path d="M8 1a2 2 0 0 1 2 2v4H6V3a2 2 0 0 1 2-2zm3 6V3a3 3 0 0 0-6 0v4a2 2 0 0 0-2 2v5a2 2 0 0 0 2 2h6a2 2 0 0 0 2-2V9a2 2 0 0 0-2-2z"/></svg>',
            'route' => 'pharmdata.volnoprodejnost',
            'active' => true,
            'order' => 5,
        ]);

        // ===== HLAVNÍ SEKCE 4: TIKETY =====
        $tickets = Module::create([
            'name' => 'Tikety',
            'slug' => 'tickets',
            'description' => 'Správa podpory a tiketů',
            'icon' => '<svg fill="currentColor" viewBox="0 0 16 16"><path d="M14 1a1 1 0 0 1 1 1v8a1 1 0 0 1-1 1H4.414A2 2 0 0 0 3 11.586l-2 2V2a1 1 0 0 1 1-1h12zM2 0a2 2 0 0 0-2 2v12.793a.5.5 0 0 0 .854.353l2.853-2.853A1 1 0 0 1 4.414 12H14a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2H2z"/><path d="M5 6a1 1 0 1 1-2 0 1 1 0 0 1 2 0zm4 0a1 1 0 1 1-2 0 1 1 0 0 1 2 0zm4 0a1 1 0 1 1-2 0 1 1 0 0 1 2 0z"/></svg>',
            'route' => null,
            'active' => true,
            'order' => 4,
        ]);

        Module::create([
            'parent_id' => $tickets->id,
            'name' => 'Seznam',
            'slug' => 'tickets-list',
            'description' => 'Seznam všech tiketů',
            'icon' => '<svg fill="currentColor" viewBox="0 0 16 16"><path d="M12 0H4a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2zM5 4h6a.5.5 0 0 1 0 1H5a.5.5 0 0 1 0-1zm-.5 2.5A.5.5 0 0 1 5 6h6a.5.5 0 0 1 0 1H5a.5.5 0 0 1-.5-.5zM5 8h6a.5.5 0 0 1 0 1H5a.5.5 0 0 1 0-1zm0 2h3a.5.5 0 0 1 0 1H5a.5.5 0 0 1 0-1z"/></svg>',
            'route' => 'tikety.index',
            'active' => true,
            'order' => 1,
        ]);
    }
}
