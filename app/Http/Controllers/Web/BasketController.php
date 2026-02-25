<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Basket;
use App\Models\BasketItem;
use App\Facades\EntityFacade;
use App\Facades\BasketFacade;
use App\Enums\BasketType;
use App\Enums\BasketItemType;
use App\Http\Controllers\Pages\ProductController as ProductControllerPages;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
use Inertia\Inertia;


class BasketController extends Controller
{
    public function index(Request $request)
    {
        $allProducts = ProductControllerPages::getAllProducts();
        return Inertia::render('Store/basket', [
            'products' => $allProducts,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'product.id' => 'integer',
            'product.price' => 'required|integer',
            'quantity' => 'required|integer|min:1',
        ]);

        $basket = $this->getOrCreateBasket($request);

        // Získej entity ID pomocí Facade
        $entityId = EntityFacade::getEntityId('product');

        // Najdi existující položku v košíku
        $existingItem = $basket->items()
            ->where('entity_id', $entityId)
            ->where('model_id', $validated['product']['id'])
            ->first();

        if ($existingItem) {
            // Aktualizuj množství
            $existingItem->quantity += $validated['quantity'];
            $existingItem->price_total = $existingItem->price * $existingItem->quantity;
            $existingItem->save();
        } else {
            // Vytvoř novou položku
            BasketItem::create([
                'basket_id' => $basket->id,
                'entity_id' => $entityId,
                'model_id' => $validated['product']['id'],
                'type' => BasketItemType::PRODUCT->value,
                'price' => $validated['product']['price'],
                'price_total' => $validated['product']['price'] * $validated['quantity'],
                'vat' => 0,
                'quantity' => $validated['quantity'],
            ]);
        }

        // Pokud je to Inertia request, vrať redirect na košík
        if ($request->header('X-Inertia')) {
            return redirect()->route('basket.index');
        }

        return $this->getBasketJson($request);
    }

    public function update(Request $request, $productId)
    {
        $validated = $request->validate([
            'quantity' => 'required|integer|min:1',
        ]);

        $basket = $this->getOrCreateBasket($request);

        $item = $basket->items()->where('id', $productId)->first();

        if ($item) {
            $item->quantity = $validated['quantity'];
            $item->price_total = $item->price * $item->quantity;
            $item->save();
        }

        // Pokud je to Inertia request, vrať data pro partial update
        if ($request->header('X-Inertia')) {
            return $this->getBasketData($request);
        }

        return $this->getBasketJson($request);
    }

    public function destroy(Request $request, $productId)
    {
        $basket = $this->getOrCreateBasket($request);

        $basket->items()->where('id', $productId)->delete();

        // Pokud je to Inertia request, vrať data pro partial update
        if ($request->header('X-Inertia')) {
            return $this->getBasketData($request);
        }

        return $this->getBasketJson($request);
    }

    public function clear(Request $request)
    {
        $basket = $this->getOrCreateBasket($request);

        $basket->items()->delete();

        // Pokud je to Inertia request, vrať data pro partial update
        if ($request->header('X-Inertia')) {
            return $this->getBasketData($request);
        }

        return $this->getBasketJson($request);
    }

    /**
     * Vrátí data košíku pro Inertia partial update
     */
    private function getBasketData(Request $request)
    {
        $basketItems = BasketFacade::getCurrent();
        $allProducts = ProductControllerPages::getAllProducts();

        $basketWithProducts = [];
        $totalPrice = 0;
        $totalItems = 0;

        foreach ($basketItems as $item) {
            $product = null;
            foreach ($allProducts as $prod) {
                if ($prod['id'] == $item['model_id']) {
                    $product = $prod;
                    break;
                }
            }

            if ($product) {
                $basketWithProducts[] = [
                    'id' => $item['id'],
                    'product_id' => $item['model_id'],
                    'product' => $product,
                    'quantity' => $item['quantity'],
                    'price' => $item['price'],
                    'price_total' => $item['price_total'],
                ];
                $totalPrice += $item['price_total'];
                $totalItems += $item['quantity'];
            }
        }

        $ensured = BasketFacade::ensureCookie($request);
        Cookie::queue($ensured['cookie']);

        return Inertia::render('Store/basket', [
            'items' => $basketWithProducts,
            'totalPrice' => $totalPrice,
            'totalItems' => $totalItems,
        ]);
    }

    /**
     * Vrátí JSON s košíkem pro API volání
     */
    private function getBasketJson(Request $request)
    {
        $basket = BasketFacade::getCurrent();
        $ensured = BasketFacade::ensureCookie($request);
        return response()->json(['basket' => $basket])->withCookie($ensured['cookie']);
    }

    private function getOrCreateBasket(Request $request)
    {
        $ensured = BasketFacade::ensureCookie($request);

        return Basket::firstOrCreate(
            ['session_id' => $ensured['key'] ],
            ['status' => BasketType::OPEN->value]
        );
    }
}
