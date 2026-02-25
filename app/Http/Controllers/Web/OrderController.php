<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Services\OrderService;
use App\Models\Order;
use App\Enums\TransportType;
use App\Http\Controllers\Pages\ProductController as ProductControllerPages;
use Illuminate\Http\Request;
use Inertia\Inertia;

class OrderController extends Controller
{
    public function __construct(
        private OrderService $orderService
    ) {}

    /**
     * Vytvoří objednávku z checkout dat
     */
    public function store(Request $request)
    {
        // Získání nastavení checkout
        $checkoutType = config('basket.checkout.type', 'advanced');

        // Určení, zda je vybraná doprava typu pickup z transport.type
        $isPickup = false;
        if ($request->has('transport.type')) {
            try {
                $transportType = TransportType::from($request->input('transport.type'));
                $isPickup = $transportType->deliveryType()->value === 'pickup';
            } catch (\ValueError $e) {
                // Pokud typ není validní, necháme default false
            }
        }

        // Určení, zda jsou adresní údaje povinné
        // V simple režimu nejsou adresní údaje povinné při pickup, v advanced jsou vždy povinné
        $addressRequired = $checkoutType === 'advanced' || !$isPickup;

        $validated = $request->validate([
            'transport' => 'required|array',
            'transport.id' => 'required|integer|exists:transports,id',
            'transport.type' => 'required|string|max:20',
            'transport.price' => 'required|numeric|min:0',
            'payment' => 'required|array',
            'payment.id' => 'required|integer|exists:payments,id',
            'payment.type' => 'required|string|max:20',
            'payment.price' => 'nullable|numeric|min:0',
            'payment.final_price' => 'nullable|numeric|min:0',
            'firstName' => 'required|string|max:255',
            'lastName' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:20',
            'street' => $addressRequired ? 'required|string|max:255' : 'nullable|string|max:255',
            'city' => $addressRequired ? 'required|string|max:255' : 'nullable|string|max:255',
            'zip' => $addressRequired ? 'required|string|max:10' : 'nullable|string|max:10',
            'country' => 'nullable|string|max:2',
            'isCompany' => 'nullable|boolean',
            'companyName' => 'nullable|string|max:255',
            'companyId' => 'nullable|string|max:20',
            'companyVatId' => 'nullable|string|max:20',
            'hasDeliveryAddress' => 'nullable|boolean',
            'deliveryFirstName' => 'nullable|string|max:255',
            'deliveryLastName' => 'nullable|string|max:255',
            'deliveryStreet' => 'nullable|string|max:255',
            'deliveryCity' => 'nullable|string|max:255',
            'deliveryZip' => 'nullable|string|max:10',
            'deliveryCountry' => 'nullable|string|max:2',
            'note' => 'nullable|string',
        ]);

        try {
            $userId = auth()->id();
            $order = $this->orderService->createOrder($validated, $userId);

            return redirect()->route('order.confirmation', ['order' => $order->id])
                ->with('success', 'Objednávka byla úspěšně vytvořena.');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => $e->getMessage()]);
        }
    }

    /**
     * Zobrazí potvrzení objednávky
     */
    public function confirmation(Request $request, Order $order)
    {
        $allProducts = ProductControllerPages::getAllProducts();

        // Propojení položek objednávky s produkty
        $orderItemsWithProducts = [];
        foreach ($order->items as $item) {
            $product = null;
            foreach ($allProducts as $prod) {
                if ($prod['id'] == $item->model_id) {
                    $product = $prod;
                    break;
                }
            }

            if ($product) {
                $orderItemsWithProducts[] = [
                    'id' => $item->id,
                    'productId' => $item->model_id,
                    'product' => $product,
                    'quantity' => $item->quantity,
                    'price' => $item->price,
                    'priceTotal' => $item->price_total,
                    'vat' => $item->vat,
                ];
            }
        }

        return Inertia::render('Store/order-confirmation', [
            'order' => $order,
            'items' => $orderItemsWithProducts,
        ]);
    }
}
