<?php

namespace App\Http\Controllers\Pages;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Http\Controllers\Pages\ProductController;

class SearchController extends Controller
{
    /**
     * Zobrazení stránky vyhledávání
     */
    public function index(Request $request)
    {
        $query = $request->get('q', '');
        $category = $request->get('category', 'all');

        $allProducts = ProductController::getAllProducts();
        $allArticles = \App\Http\Controllers\Pages\ArticleController::getAllArticles();

        // Filtrování produktů podle dotazu
        $filteredProducts = [];
        if (!empty($query)) {
            $filteredProducts = array_filter($allProducts, function($product) use ($query) {
                $searchTerm = strtolower($query);
                $name = strtolower($product['name']);
                $description = strtolower($product['description'] ?? '');

                return strpos($name, $searchTerm) !== false ||
                       strpos($description, $searchTerm) !== false;
            });
        }

        // Filtrování článků podle dotazu
        $filteredArticles = [];
        if (!empty($query)) {
            $filteredArticles = array_filter($allArticles, function($article) use ($query) {
                $searchTerm = strtolower($query);
                $title = strtolower($article['title']);
                $excerpt = strtolower($article['excerpt'] ?? '');

                return strpos($title, $searchTerm) !== false ||
                       strpos($excerpt, $searchTerm) !== false;
            });
        }

        // Kategorie pro vyhledávání
        $categories = [
            ['id' => 'all', 'name' => 'Všechny kategorie', 'icon' => '📦', 'count' => count($allProducts)],
            ['id' => 'medicine', 'name' => 'Léky', 'icon' => '💊', 'count' => 0],
            ['id' => 'wellness', 'name' => 'Zdraví', 'icon' => '❤️', 'count' => 0],
            ['id' => 'diagnostic', 'name' => 'Diagnostika', 'icon' => '🔬', 'count' => 0],
            ['id' => 'health-corner', 'name' => 'Zdravotní koutek', 'icon' => '🏥', 'count' => 0],
            ['id' => 'others', 'name' => 'Ostatní', 'icon' => '📋', 'count' => 0],
        ];

        // Doporučené produkty pro výchozí zobrazení (první 4 produkty)
        $featuredProducts = empty($query) ? array_slice($allProducts, 0, 4) : [];

        return Inertia::render('Search', [
            'query' => $query,
            'category' => $category,
            'products' => array_values($filteredProducts),
            'articles' => array_values($filteredArticles),
            'categories' => $categories,
            'totalResults' => count($filteredProducts) + count($filteredArticles),
            'featuredProducts' => $featuredProducts,
        ]);
    }
}

