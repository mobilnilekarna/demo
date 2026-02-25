<?php

namespace App\Http\Controllers\Pages;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Str;

class IndexController extends Controller
{
    public function index()
    {
        $products = \App\Http\Controllers\Pages\ProductController::getAllProducts();

        // Akční produkty (prvních 8 pro swiper)
        $featuredProducts = array_slice($products, 0, 8);

        // Doporučené produkty (všech 8 produktů, aby bylo možné swipovat)
        $recommendedProducts = array_slice($products, 0, 8);

        // Články z ArticleController (prvních 10 pro swiper)
        $allArticles = ArticleController::getAllArticles();
        $articles = array_slice($allArticles, 0, 10);

        // Odstranění content z článků pro homepage (není potřeba)
        foreach ($articles as &$article) {
            unset($article['content']);
        }

        // Promo bannery
        $banners = [
            [
                'variant' => 'product',
                'headerBadge' => 'Pilulka Friday',
                'title' => 'Pilulka PRO se slevou 50 %',
                'image' => 'https://images.unsplash.com/photo-1506126613408-eca07ce68773?w=400',
                'price' => '499 Kč',
                'priceOld' => '999 Kč',
                'buttonText' => 'Objevit',
                'url' => '/products/pilulka-pro',
            ],
            [
                'variant' => 'product',
                'headerBadge' => 'Pilulka Friday',
                'title' => 'Vitamin D3 + K2 se slevou 40 %',
                'image' => 'https://images.unsplash.com/photo-1584308666744-24d5c474f2ae?w=400',
                'price' => '299 Kč',
                'priceOld' => '499 Kč',
                'buttonText' => 'Objevit',
                'url' => '/products/vitamin-d3-k2',
            ],
            [
                'variant' => 'product',
                'headerBadge' => 'Pilulka Friday',
                'title' => 'Omega-3 Premium se slevou 35 %',
                'image' => 'https://images.unsplash.com/photo-1559827260-dc66d52bef19?w=400',
                'price' => '449 Kč',
                'priceOld' => '689 Kč',
                'buttonText' => 'Objevit',
                'url' => '/products/omega-3',
            ],
            [
                'variant' => 'product',
                'headerBadge' => 'Pilulka Friday',
                'title' => 'Probiotika Complex se slevou 45 %',
                'image' => 'https://images.unsplash.com/photo-1587854692152-cbe660dbde88?w=400',
                'price' => '349 Kč',
                'priceOld' => '629 Kč',
                'buttonText' => 'Objevit',
                'url' => '/products/probiotika',
            ],
            [
                'variant' => 'product',
                'headerBadge' => 'Pilulka Friday',
                'title' => 'Medicube Zpevňující maska se slevou 41 %',
                'image' => 'https://images.unsplash.com/photo-1556228720-195a672e8a03?w=400',
                'price' => '399 Kč',
                'priceOld' => '679 Kč',
                'buttonText' => 'Objevit',
                'url' => '/products/medicube-mask',
            ],
            [
                'variant' => 'product',
                'headerBadge' => 'Pilulka Friday',
                'title' => 'Magnesium Complex se slevou 38 %',
                'image' => 'https://images.unsplash.com/photo-1559757148-5c350d0d3c56?w=400',
                'price' => '279 Kč',
                'priceOld' => '449 Kč',
                'buttonText' => 'Objevit',
                'url' => '/products/magnesium',
            ],
            [
                'variant' => 'product',
                'headerBadge' => 'Pilulka Friday',
                'title' => 'Vitamin C 1000mg se slevou 42 %',
                'image' => 'https://images.unsplash.com/photo-1587854692152-cbe660dbde88?w=400',
                'price' => '199 Kč',
                'priceOld' => '349 Kč',
                'buttonText' => 'Objevit',
                'url' => '/products/vitamin-c',
            ],
            [
                'variant' => 'product',
                'headerBadge' => 'Pilulka Friday',
                'title' => 'Zinek + Selen se slevou 33 %',
                'image' => 'https://images.unsplash.com/photo-1559757175-0eb30cd8c063?w=400',
                'price' => '229 Kč',
                'priceOld' => '339 Kč',
                'buttonText' => 'Objevit',
                'url' => '/products/zinek-selen',
            ],
            [
                'variant' => 'product',
                'headerBadge' => 'Pilulka Friday',
                'title' => 'Kurkuma Extra se slevou 36 %',
                'image' => 'https://images.unsplash.com/photo-1584308666744-24d5c474f2ae?w=400',
                'price' => '319 Kč',
                'priceOld' => '499 Kč',
                'buttonText' => 'Objevit',
                'url' => '/products/kurkuma',
            ],
            [
                'variant' => 'product',
                'headerBadge' => 'Pilulka Friday',
                'title' => 'Kollagen Marine se slevou 44 %',
                'image' => 'https://images.unsplash.com/photo-1559827260-dc66d52bef19?w=400',
                'price' => '449 Kč',
                'priceOld' => '799 Kč',
                'buttonText' => 'Objevit',
                'url' => '/products/kollagen',
            ],
            [
                'variant' => 'product',
                'headerBadge' => 'Pilulka Friday',
                'title' => 'B-komplex Forte se slevou 37 %',
                'image' => 'https://images.unsplash.com/photo-1506126613408-eca07ce68773?w=400',
                'price' => '249 Kč',
                'priceOld' => '399 Kč',
                'buttonText' => 'Objevit',
                'url' => '/products/b-komplex',
            ],
            [
                'variant' => 'product',
                'headerBadge' => 'Pilulka Friday',
                'title' => 'Ashwagandha Premium se slevou 39 %',
                'image' => 'https://images.unsplash.com/photo-1587854692152-cbe660dbde88?w=400',
                'price' => '369 Kč',
                'priceOld' => '599 Kč',
                'buttonText' => 'Objevit',
                'url' => '/products/ashwagandha',
            ],
            [
                'variant' => 'product',
                'headerBadge' => 'Pilulka Friday',
                'title' => 'Q10 Koenzym se slevou 43 %',
                'image' => 'https://images.unsplash.com/photo-1559757148-5c350d0d3c56?w=400',
                'price' => '429 Kč',
                'priceOld' => '749 Kč',
                'buttonText' => 'Objevit',
                'url' => '/products/q10',
            ],
            [
                'variant' => 'product',
                'headerBadge' => 'Pilulka Friday',
                'title' => 'Ginkgo Biloba se slevou 34 %',
                'image' => 'https://images.unsplash.com/photo-1559757175-0eb30cd8c063?w=400',
                'price' => '259 Kč',
                'priceOld' => '389 Kč',
                'buttonText' => 'Objevit',
                'url' => '/products/ginkgo',
            ],
            [
                'variant' => 'product',
                'headerBadge' => 'Pilulka Friday',
                'title' => 'Železo + Kyselina listová se slevou 40 %',
                'image' => 'https://images.unsplash.com/photo-1584308666744-24d5c474f2ae?w=400',
                'price' => '179 Kč',
                'priceOld' => '299 Kč',
                'buttonText' => 'Objevit',
                'url' => '/products/zelezo',
            ],
            [
                'variant' => 'product',
                'headerBadge' => 'Pilulka Friday',
                'title' => 'Melatonin 3mg se slevou 35 %',
                'image' => 'https://images.unsplash.com/photo-1559827260-dc66d52bef19?w=400',
                'price' => '219 Kč',
                'priceOld' => '339 Kč',
                'buttonText' => 'Objevit',
                'url' => '/products/melatonin',
            ],
            [
                'variant' => 'product',
                'headerBadge' => 'Pilulka Friday',
                'title' => 'Spirulina Organic se slevou 46 %',
                'image' => 'https://images.unsplash.com/photo-1506126613408-eca07ce68773?w=400',
                'price' => '329 Kč',
                'priceOld' => '609 Kč',
                'buttonText' => 'Objevit',
                'url' => '/products/spirulina',
            ],
            [
                'variant' => 'product',
                'headerBadge' => 'Pilulka Friday',
                'title' => 'Chlorella Tablety se slevou 32 %',
                'image' => 'https://images.unsplash.com/photo-1587854692152-cbe660dbde88?w=400',
                'price' => '269 Kč',
                'priceOld' => '399 Kč',
                'buttonText' => 'Objevit',
                'url' => '/products/chlorella',
            ],
        ];

        $brands = [
            [
                'id' => 1,
                'name' => 'Brand 1',
                'image' => asset('vichy.jpg'),
            ],
            [
                'id' => 2,
                'name' => 'Brand 2',
                'image' => asset('vichy.jpg'),
            ],
            [
                'id' => 3,
                'name' => 'Brand 3',
                'image' => asset('vichy.jpg'),
            ],
            [
                'id' => 4,
                'name' => 'Brand 4',
                'image' => asset('vichy.jpg'),
            ],
            [
                'id' => 5,
                'name' => 'Brand 5',
                'image' => asset('vichy.jpg'),
            ],
            [
                'id' => 6,
                'name' => 'Brand 6',
                'image' => asset('vichy.jpg'),
            ],
            [
                'id' => 7,
                'name' => 'Brand 7',
                'image' => asset('vichy.jpg'),
            ],
            [
                'id' => 8,
                'name' => 'Brand 8',
                'image' => asset('vichy.jpg'),
            ],
        ];

        return Inertia::render('Index', [
            'featuredProducts' => $featuredProducts,
            'recommendedProducts' => $recommendedProducts,
            'articles' => $articles,
            'banners' => $banners,
            'brands' => $brands,
        ]);
    }

    public function about()
    {
        return Inertia::render('About', [
            'message' => 'O nás',
        ]);
    }

    public function contact()
    {
        return Inertia::render('Contact');
    }
}
