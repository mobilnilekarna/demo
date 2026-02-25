<?php

namespace App\Http\Controllers\Pages;

use App\Http\Controllers\Controller;
use App\Services\ShippingService;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Http\Controllers\Pages\ProductController as ProductControllerPages;

class CheckoutController extends Controller
{
    public function __construct(
        private ShippingService $shippingService
    ) {
    }

    public function index(Request $request)
    {
        $transports = $this->shippingService->getTransportsWithPayments();
        $allProducts = ProductControllerPages::getAllProducts();

        return Inertia::render('Store/checkout', [
            'transports' => $transports,
            'products' => $allProducts,
        ]);
    }
}

