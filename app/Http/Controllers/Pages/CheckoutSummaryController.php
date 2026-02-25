<?php

namespace App\Http\Controllers\Pages;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Http\Controllers\Pages\ProductController as ProductControllerPages;

class CheckoutSummaryController extends Controller
{
    public function index(Request $request)
    {
        $allProducts = ProductControllerPages::getAllProducts();

        return Inertia::render('Store/checkout-summary', [
            'products' => $allProducts,
        ]);
    }
}

