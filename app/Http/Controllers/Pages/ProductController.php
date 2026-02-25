<?php

namespace App\Http\Controllers\Pages;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    /**
     * Vytvoří slug z názvu produktu
     */
    private static function generateSlug($name)
    {
        return Str::slug($name);
    }

    /**
     * Generuje pole 30 recenzí pro produkt
     */
    private static function generateReviews()
    {
        $names = [
            'Jan Novák', 'Marie Svobodová', 'Petr Dvořák', 'Eva Horáková', 'Tomáš Němec',
            'Anna Marková', 'Jiří Pokorný', 'Lucie Veselá', 'Martin Krejčí', 'Kateřina Procházková',
            'Pavel Černý', 'Tereza Králová', 'David Jelínek', 'Monika Růžičková', 'Jakub Beneš',
            'Veronika Fiala', 'Michal Šťastný', 'Zuzana Sedláčková', 'František Kolář', 'Barbora Havlíčková',
            'Ondřej Bartoš', 'Simona Urbanová', 'Lukáš Marek', 'Petra Vojtová', 'Robert Hájek',
            'Martina Blažková', 'Filip Kořínek', 'Nikola Šimková', 'Adam Kratochvíl', 'Klára Pospíšilová'
        ];

        $positiveTexts = [
            'Výborný produkt, splnil veškerá očekávání. Doporučuji!',
            'Skvělá kvalita za rozumnou cenu. Jsem velmi spokojený/á.',
            'Rychlé dodání, produkt přesně odpovídá popisu. Děkuji!',
            'Používám už měsíc a jsem nadšený/á. Kvalita je výborná.',
            'Nejlepší nákup, jaký jsem kdy udělal/a. Jednoznačně doporučuji.',
            'Produkt předčil má očekávání. Balení bylo perfektní.',
            'Vynikající zákaznický servis a skvělý produkt.',
            'Už jsem objednal/a i pro celou rodinu. Velmi spokojený/á.',
            'Konečně kvalitní produkt. Stojí za tu cenu.',
            'Perfektní zpracování, moderní design. Super!',
        ];

        $neutralTexts = [
            'Produkt je v pořádku, ale čekal/a jsem trochu víc.',
            'Splňuje základní požadavky. Za tu cenu ok.',
            'Nic extra, ale ani špatné. Průměrný produkt.',
            'Dodání trvalo déle, ale produkt je funkční.',
            'Mohl by být lepší, ale pro běžné použití stačí.',
        ];

        $negativeTexts = [
            'Trochu zklamání, kvalita mohla být lepší.',
            'Očekával/a jsem víc za tuto cenu.',
        ];

        $reviews = [];

        for ($i = 0; $i < 30; $i++) {
            $rating = self::getWeightedRating();

            if ($rating >= 4) {
                $text = $positiveTexts[array_rand($positiveTexts)];
            } elseif ($rating >= 3) {
                $text = $neutralTexts[array_rand($neutralTexts)];
            } else {
                $text = $negativeTexts[array_rand($negativeTexts)];
            }

            $daysAgo = rand(1, 365);
            $date = now()->subDays($daysAgo);

            $reviews[] = [
                'id' => $i + 1,
                'author' => $names[$i],
                'rating' => $rating,
                'text' => $text,
                'date' => $date->format('d.m.Y'),
                'date_iso' => $date->toISOString(),
                'verified' => rand(0, 1) === 1,
                'helpful_count' => rand(0, 25),
            ];
        }

        // Seřadit podle data (nejnovější první)
        usort($reviews, function($a, $b) {
            return strtotime($b['date_iso']) - strtotime($a['date_iso']);
        });

        return $reviews;
    }

    /**
     * Vrací hodnocení s váženou pravděpodobností (více vyšších hodnocení)
     */
    private static function getWeightedRating()
    {
        $weights = [
            5 => 45,  // 45% šance na 5 hvězdiček
            4 => 30,  // 30% šance na 4 hvězdičky
            3 => 15,  // 15% šance na 3 hvězdičky
            2 => 7,   // 7% šance na 2 hvězdičky
            1 => 3,   // 3% šance na 1 hvězdičku
        ];

        $rand = rand(1, 100);
        $cumulative = 0;

        foreach ($weights as $rating => $weight) {
            $cumulative += $weight;
            if ($rand <= $cumulative) {
                return $rating;
            }
        }

        return 5;
    }

    /**
     * Spočítá statistiky hodnocení z recenzí
     */
    private static function calculateRatingStats($reviews)
    {
        $total = count($reviews);
        $sum = 0;
        $distribution = [1 => 0, 2 => 0, 3 => 0, 4 => 0, 5 => 0];

        foreach ($reviews as $review) {
            $sum += $review['rating'];
            $distribution[$review['rating']]++;
        }

        $percentages = [];
        foreach ($distribution as $rating => $count) {
            $percentages[$rating] = $total > 0 ? round(($count / $total) * 100) : 0;
        }

        return [
            'average' => $total > 0 ? round($sum / $total, 1) : 0,
            'total' => $total,
            'distribution' => $distribution,
            'percentages' => $percentages,
        ];
    }

    /**
     * Získání všech produktů
     */
    public static function getAllProducts()
    {
        $products = [
            [
                'id' => 1,
                'name' => 'Laptop Pro',
                'description' => 'Výkonný notebook pro profesionály',
                'price' => 250,
                'stock' => 5,
                'image' => 'https://picsum.photos/400/400?random=1',
                'badges' => ['TIP'],
            ],
            [
                'id' => 2,
                'name' => 'Smartphone X',
                'description' => 'Nejnovější model s pokročilými funkcemi',
                'price' => 500,
                'stock' => 10,
                'image' => 'https://picsum.photos/400/400?random=2',
                'badges' => ['Doporučujeme', 'Doprava zdarma'],
            ],
            [
                'id' => 3,
                'name' => 'Tablet Air',
                'description' => 'Lehký a elegantní tablet',
                'price' => 330,
                'stock' => 8,
                'image' => 'https://picsum.photos/400/400?random=3',
            ],
            [
                'id' => 4,
                'name' => 'Sluchátka Pro',
                'description' => 'Bezdrátová sluchátka s aktivním potlačením hluku',
                'price' => 5000,
                'stock' => 15,
                'image' => 'https://picsum.photos/400/400?random=4',
                'badges' => ['Doprava zdarma'],
            ],
            [
                'id' => 5,
                'name' => 'Myš Gaming',
                'description' => 'Precizní myš pro hráče',
                'price' => 2000,
                'stock' => 20,
                'image' => 'https://picsum.photos/400/400?random=5',
            ],
            [
                'id' => 6,
                'name' => 'Klávesnice Mechanická',
                'description' => 'Mechanická klávesnice s RGB osvětlením',
                'price' => 3000,
                'stock' => 12,
                'image' => 'https://picsum.photos/400/400?random=6',
                'badges' => ['TIP'],
            ],
            [
                'id' => 7,
                'name' => 'Monitor 4K',
                'description' => '27" monitor s 4K rozlišením',
                'price' => 8000,
                'stock' => 6,
                'image' => 'https://picsum.photos/400/400?random=7',
                'badges' => ['Doporučujeme'],
            ],
            [
                'id' => 8,
                'name' => 'Webová kamera',
                'description' => 'HD webová kamera pro videohovory',
                'price' => 1500,
                'stock' => 25,
                'image' => 'https://picsum.photos/400/400?random=8',
            ],
            [
                'id' => 9,
                'name' => 'Reproduktory Bluetooth',
                'description' => 'Výkonné bezdrátové reproduktory s basy',
                'price' => 2500,
                'stock' => 18,
                'image' => 'https://picsum.photos/400/400?random=9',
                'badges' => ['Doporučujeme'],
            ],
            [
                'id' => 10,
                'name' => 'Grafická karta',
                'description' => 'Herní grafická karta s ray tracing',
                'price' => 12000,
                'stock' => 4,
                'image' => 'https://picsum.photos/400/400?random=10',
                'badges' => ['TIP', 'Doprava zdarma'],
            ],
            [
                'id' => 11,
                'name' => 'SSD Disk 1TB',
                'description' => 'Rychlý SSD disk pro upgrade počítače',
                'price' => 1800,
                'stock' => 30,
                'image' => 'https://picsum.photos/400/400?random=11',
            ],
            [
                'id' => 12,
                'name' => 'Router WiFi 6',
                'description' => 'Moderní router s podporou WiFi 6',
                'price' => 2200,
                'stock' => 14,
                'image' => 'https://picsum.photos/400/400?random=12',
                'badges' => ['Doporučujeme'],
            ],
            [
                'id' => 13,
                'name' => 'Mikrofon USB',
                'description' => 'Profesionální USB mikrofon pro streamování',
                'price' => 2800,
                'stock' => 9,
                'image' => 'https://picsum.photos/400/400?random=13',
            ],
            [
                'id' => 14,
                'name' => 'Nabíječka bezdrátová',
                'description' => 'Bezdrátová nabíječka pro smartphony',
                'price' => 800,
                'stock' => 22,
                'image' => 'https://picsum.photos/400/400?random=14',
                'badges' => ['Doprava zdarma'],
            ],
            [
                'id' => 15,
                'name' => 'Powerbank 20000mAh',
                'description' => 'Výkonná powerbank s rychlým nabíjením',
                'price' => 1200,
                'stock' => 16,
                'image' => 'https://picsum.photos/400/400?random=15',
            ],
            [
                'id' => 16,
                'name' => 'Notebook Ultra',
                'description' => 'Tenký a lehký notebook s dlouhou výdrží baterie',
                'price' => 15000,
                'stock' => 7,
                'image' => 'https://picsum.photos/400/400?random=16',
                'badges' => ['TIP'],
            ],
            [
                'id' => 17,
                'name' => 'Chytré hodinky',
                'description' => 'Fitness hodinky s GPS a monitorováním zdraví',
                'price' => 3500,
                'stock' => 12,
                'image' => 'https://picsum.photos/400/400?random=17',
                'badges' => ['Doporučujeme'],
            ],
            [
                'id' => 18,
                'name' => 'Externí disk 2TB',
                'description' => 'Přenosný externí disk s vysokou rychlostí',
                'price' => 2200,
                'stock' => 20,
                'image' => 'https://picsum.photos/400/400?random=18',
            ],
            [
                'id' => 19,
                'name' => 'Webkamera 4K',
                'description' => 'Profesionální webkamera s automatickým ostřením',
                'price' => 3200,
                'stock' => 8,
                'image' => 'https://picsum.photos/400/400?random=19',
                'badges' => ['Doprava zdarma'],
            ],
            [
                'id' => 20,
                'name' => 'Herní židle',
                'description' => 'Ergonomická herní židle s podporou zad',
                'price' => 4500,
                'stock' => 5,
                'image' => 'https://picsum.photos/400/400?random=20',
            ],
            [
                'id' => 21,
                'name' => 'LED pásek RGB',
                'description' => 'Barevný LED pásek s dálkovým ovládáním',
                'price' => 600,
                'stock' => 35,
                'image' => 'https://picsum.photos/400/400?random=21',
            ],
            [
                'id' => 22,
                'name' => 'USB hub',
                'description' => 'USB hub s 7 porty a rychlým přenosem',
                'price' => 450,
                'stock' => 28,
                'image' => 'https://picsum.photos/400/400?random=22',
            ],
            [
                'id' => 23,
                'name' => 'Kabel USB-C',
                'description' => 'Rychlý nabíjecí kabel USB-C 2m',
                'price' => 300,
                'stock' => 50,
                'image' => 'https://picsum.photos/400/400?random=23',
                'badges' => ['Doprava zdarma'],
            ],
            [
                'id' => 24,
                'name' => 'Stolní lampa LED',
                'description' => 'Moderní stolní lampa s nastavitelným jasem',
                'price' => 1200,
                'stock' => 15,
                'image' => 'https://picsum.photos/400/400?random=24',
            ],
            [
                'id' => 25,
                'name' => 'Podložka pod myš',
                'description' => 'Velká podložka pod myš s gelovou opěrkou',
                'price' => 350,
                'stock' => 40,
                'image' => 'https://picsum.photos/400/400?random=25',
            ],
            [
                'id' => 26,
                'name' => 'Sluchátka s mikrofonem',
                'description' => 'Herní sluchátka s prostorovým zvukem',
                'price' => 1800,
                'stock' => 11,
                'image' => 'https://picsum.photos/400/400?random=26',
                'badges' => ['TIP'],
            ],
            [
                'id' => 27,
                'name' => 'Kamera pro streamování',
                'description' => '4K kamera s automatickým ostřením',
                'price' => 5500,
                'stock' => 6,
                'image' => 'https://picsum.photos/400/400?random=27',
            ],
            [
                'id' => 28,
                'name' => 'Mikrofonní stojan',
                'description' => 'Nastavitelný stojan pro mikrofon',
                'price' => 800,
                'stock' => 18,
                'image' => 'https://picsum.photos/400/400?random=28',
            ],
            [
                'id' => 29,
                'name' => 'Zvuková karta USB',
                'description' => 'Externí zvuková karta pro nahrávání',
                'price' => 2400,
                'stock' => 9,
                'image' => 'https://picsum.photos/400/400?random=29',
            ],
            [
                'id' => 30,
                'name' => 'Kabel HDMI 2.1',
                'description' => 'Vysokorychlostní HDMI kabel 3m',
                'price' => 550,
                'stock' => 32,
                'image' => 'https://picsum.photos/400/400?random=30',
            ],
            [
                'id' => 31,
                'name' => 'Adaptér USB-C na HDMI',
                'description' => 'Univerzální adaptér pro připojení monitoru',
                'price' => 750,
                'stock' => 24,
                'image' => 'https://picsum.photos/400/400?random=31',
            ],
            [
                'id' => 32,
                'name' => 'Chladicí podložka',
                'description' => 'Chladicí podložka pro notebook s LED osvětlením',
                'price' => 950,
                'stock' => 13,
                'image' => 'https://picsum.photos/400/400?random=32',
            ],
            [
                'id' => 33,
                'name' => 'Stolní ventilátor',
                'description' => 'Tichý stolní ventilátor s USB napájením',
                'price' => 450,
                'stock' => 27,
                'image' => 'https://picsum.photos/400/400?random=33',
            ],
            [
                'id' => 34,
                'name' => 'Kabel Lightning',
                'description' => 'Originální Lightning kabel 1m',
                'price' => 400,
                'stock' => 45,
                'image' => 'https://picsum.photos/400/400?random=34',
            ],
            [
                'id' => 35,
                'name' => 'Pouzdro na notebook',
                'description' => 'Ochrané pouzdro s polstrováním',
                'price' => 650,
                'stock' => 19,
                'image' => 'https://picsum.photos/400/400?random=35',
            ],
            [
                'id' => 36,
                'name' => 'Stojan na monitor',
                'description' => 'Nastavitelný stojan pro jeden monitor',
                'price' => 1200,
                'stock' => 10,
                'image' => 'https://picsum.photos/400/400?random=36',
            ],
        ];

        // Přidání slug ke každému produktu
        foreach ($products as &$product) {
            $product['slug'] = self::generateSlug($product['name']);
        }

        return $products;
    }

    /**
     * Zobrazení seznamu produktů s paginací
     */
    public function index(Request $request)
    {
        $allProducts = self::getAllProducts();

        // Počet produktů na stránku
        $perPage = 8;
        $currentPage = (int) $request->get('page', 1);

        // Aplikování řazení pokud je zadáno
        $sort = $request->get('sort', 'default');
        if ($sort !== 'default') {
            $allProducts = self::applySorting($allProducts, $sort);
        }

        // Aplikování filtrů pokud jsou zadány
        $filters = $request->get('filters', []);
        if (!empty($filters)) {
            $allProducts = self::applyFilters($allProducts, $filters);
        }

        // Vytvoření paginace
        $total = count($allProducts);
        $products = array_slice($allProducts, ($currentPage - 1) * $perPage, $perPage);

        // Vytvoření paginace objektu podobného Laravel pagination
        $pagination = [
            'current_page' => $currentPage,
            'per_page' => $perPage,
            'total' => $total,
            'last_page' => (int) ceil($total / $perPage),
            'from' => $total > 0 ? ($currentPage - 1) * $perPage + 1 : 0,
            'to' => min($currentPage * $perPage, $total),
        ];

        return Inertia::render('Product/index', [
            'categories' => collect([
                '0' => [
                    'id' => 1,
                    'name' => 'Kategorie 1'
                ],
                '1' => [
                    'id' => 2,
                    'name' => 'Kategorie 2'
                ],
                '2' => [
                    'id' => 3,
                    'name' => 'Kategorie 3'
                ]
            ]),
            'products' => $products,
            'pagination' => $pagination,
            'filters' => self::getAvailableFilters(),
            'currentFilters' => $filters,
            'currentSort' => $sort,
            'message' => 'Naše produkty',
        ]);
    }

    /**
     * Aplikuje řazení na produkty
     */
    private static function applySorting($products, $sort)
    {
        $sorted = $products;

        switch ($sort) {
            case 'price_asc':
                usort($sorted, function($a, $b) {
                    return $a['price'] <=> $b['price'];
                });
                break;
            case 'price_desc':
                usort($sorted, function($a, $b) {
                    return $b['price'] <=> $a['price'];
                });
                break;
            case 'name_asc':
                usort($sorted, function($a, $b) {
                    return strcmp($a['name'], $b['name']);
                });
                break;
            case 'name_desc':
                usort($sorted, function($a, $b) {
                    return strcmp($b['name'], $a['name']);
                });
                break;
        }

        return $sorted;
    }

    /**
     * Aplikuje filtry na produkty
     */
    private static function applyFilters($products, $filters)
    {
        $filtered = $products;

        foreach ($filters as $key => $value) {
            if (empty($value)) {
                continue;
            }

            $filtered = array_filter($filtered, function($product) use ($key, $value) {
                // Jednoduchá implementace - lze rozšířit podle potřeby
                if (is_array($value)) {
                    return in_array($product[$key] ?? null, $value);
                }
                return isset($product[$key]) && $product[$key] == $value;
            });
        }

        return array_values($filtered);
    }

    /**
     * Vrací dostupné filtry pro frontend
     */
    private static function getAvailableFilters()
    {
        return [
            [
                'key' => 'category',
                'label' => 'Kategorie',
                'type' => 'select',
                'options' => [
                    ['value' => '1', 'label' => 'Elektronika'],
                    ['value' => '2', 'label' => 'Počítače'],
                    ['value' => '3', 'label' => 'Mobily'],
                ],
            ],
            [
                'key' => 'price_range',
                'label' => 'Cenové rozpětí',
                'type' => 'select',
                'options' => [
                    ['value' => '0-1000', 'label' => 'Do 1000 Kč'],
                    ['value' => '1000-3000', 'label' => '1000 - 3000 Kč'],
                    ['value' => '3000-5000', 'label' => '3000 - 5000 Kč'],
                    ['value' => '5000+', 'label' => 'Nad 5000 Kč'],
                ],
            ],
        ];
    }

    /**
     * Zobrazení detailu produktu podle slug
     */
    public function show($slug)
    {
        $allProducts = self::getAllProducts();

        // Najít produkt podle slug
        $product = null;
        foreach ($allProducts as $item) {
            if ($item['slug'] === $slug) {
                $product = $item;
                break;
            }
        }

        // Pokud produkt neexistuje, vrátíme 404
        if (!$product) {
            abort(404, 'Produkt nenalezen');
        }

        // Generování recenzí a statistik
        $reviews = self::generateReviews();
        $ratingStats = self::calculateRatingStats($reviews);

        // Rozšíření produktu o detailní informace pro zobrazení
        $productDetail = array_merge($product, [
            'original_price' => $product['price'] * 1.2, // 20% navíc jako původní cena
            'currency' => 'CZK',
            'in_stock' => $product['stock'] > 0,
            'stock_quantity' => $product['stock'],
            'rating' => $ratingStats['average'],
            'reviews_count' => $ratingStats['total'],
            'reviews' => $reviews,
            'rating_stats' => $ratingStats,
            'images' => [
                $product['image'],
                $product['image'],
                $product['image'],
                $product['image'],
            ],
            'specifications' => [
                'Kategorie' => 'Elektronika',
                'Záruka' => '24 měsíců',
                'Dodací lhůta' => '1-2 pracovní dny',
            ],
            'category' => [
                'id' => 1,
                'name' => 'Elektronika',
            ],
            'brand' => 'TechPro',
            'warranty' => '24 měsíců',
            'delivery_time' => '1-2 pracovní dny',
        ]);

        $relatedProducts = array_slice($allProducts, 0, 10);

        return Inertia::render('Product/show', [
            'product' => $productDetail,
            'relatedProducts' => $relatedProducts,
        ]);
    }

    /**
     * API endpoint pro získání produktů (JSON)
     */
    public function api(Request $request)
    {
        $products = self::getAllProducts();

        return response()->json(['products' => $products]);
    }
}

