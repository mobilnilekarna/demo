<?php

namespace App\Services;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Transport;
use App\Models\Payment;
use App\Models\Shipping;
use App\Facades\BasketFacade;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class OrderService
{
    /**
     * Vytvoří objednávku z checkout dat a košíku
     *
     * @param array $checkoutData
     * @param int|null $userId
     * @return Order
     */
    public function createOrder(array $checkoutData, ?int $userId = null): Order
    {
        return DB::transaction(function () use ($checkoutData, $userId) {
            // Získání aktuálního košíku
            $basketItems = BasketFacade::getCurrent();
            
            if (empty($basketItems)) {
                throw new \Exception('Košík je prázdný');
            }

            // Získání transport a payment objektů
            $transport = null;
            $payment = null;
            $transportPrice = 0;
            $transportVat = 0;
            $transportType = null;
            $paymentPrice = 0;
            $paymentVat = 0;
            $paymentType = null;

            if (isset($checkoutData['transport']) && is_array($checkoutData['transport'])) {
                // Transport je objekt z frontendu
                $transportId = $checkoutData['transport']['id'] ?? null;
                if ($transportId) {
                    $transport = Transport::find($transportId);
                }
                $transportPrice = (float) ($checkoutData['transport']['price'] ?? 0);
                $transportType = $checkoutData['transport']['type'] ?? null;
                // Výpočet VAT z ceny (21% DPH v ČR)
                $transportVat = round($transportPrice * 0.21 / 1.21, 2);
            } elseif (isset($checkoutData['transport_id'])) {
                // Fallback pro starý formát
                $transport = Transport::find($checkoutData['transport_id']);
                if ($transport) {
                    $transportPrice = (float) $transport->price;
                    $transportType = $transport->type;
                    $transportVat = round($transportPrice * 0.21 / 1.21, 2);
                }
            }

            if (isset($checkoutData['payment']) && is_array($checkoutData['payment'])) {
                // Payment je objekt z frontendu
                $paymentId = $checkoutData['payment']['id'] ?? null;
                if ($paymentId) {
                    $payment = Payment::find($paymentId);
                }
                $paymentType = $checkoutData['payment']['type'] ?? null;
            } elseif (isset($checkoutData['payment_id'])) {
                // Fallback pro starý formát
                $payment = Payment::find($checkoutData['payment_id']);
                if ($payment) {
                    $paymentType = $payment->type;
                }
            }

            // Získání finální ceny z Shipping vazby (kombinace transport + payment)
            $shipping = null;
            if ($transport && $payment) {
                $shipping = Shipping::where('transport_id', $transport->id)
                    ->where('payment_id', $payment->id)
                    ->first();
                
                if ($shipping) {
                    // Finální cena z Shipping vazby (může být specifická cena nebo součet)
                    $paymentPrice = (float) $shipping->final_price;
                } else {
                    // Fallback: pokud Shipping vazba neexistuje, použijeme součet
                    $paymentPrice = $transportPrice + ($payment ? (float) $payment->price : 0);
                }
            } else {
                // Fallback: pokud nemáme transport nebo payment, použijeme cenu z payment objektu
                if (isset($checkoutData['payment']['final_price'])) {
                    $paymentPrice = (float) $checkoutData['payment']['final_price'];
                } elseif (isset($checkoutData['payment']['price'])) {
                    $paymentPrice = (float) $checkoutData['payment']['price'];
                } else {
                    $paymentPrice = $payment ? (float) $payment->price : 0;
                }
            }

            // Výpočet VAT z finální ceny (21% DPH v ČR)
            $paymentVat = round($paymentPrice * 0.21 / 1.21, 2);

            // Výpočet cen
            $subtotal = 0;
            $vatTotal = 0;
            $shippingPrice = $paymentPrice; // Finální cena z Shipping vazby

            foreach ($basketItems as $item) {
                $subtotal += $item['price_total'];
                // VAT je již v price_total, takže počítáme pouze celkový VAT z položky
                $vatTotal += ($item['vat'] ?? 0) * $item['quantity'];
            }

            // Přidat VAT z dopravy a platby
            $vatTotal += $transportVat + $paymentVat;

            $total = $subtotal + $shippingPrice;

            // Generování čísla objednávky
            $orderNumber = $this->generateOrderNumber();

            // Vytvoření objednávky
            $order = Order::create([
                'order_number' => $orderNumber,
                'status' => 'pending',
                'user_id' => $userId,
                'transport_id' => $transport?->id,
                'transport_type' => $transport?->type ?? $transportType ?? null,
                'transport_price' => $transportPrice,
                'transport_vat' => $transportVat,
                'payment_id' => $payment?->id,
                'payment_type' => $payment?->type ?? $paymentType ?? null,
                'payment_price' => $paymentPrice, // Finální cena z Shipping vazby
                'payment_vat' => $paymentVat,
                'first_name' => $checkoutData['firstName'],
                'last_name' => $checkoutData['lastName'],
                'email' => $checkoutData['email'],
                'phone' => $checkoutData['phone'],
                'street' => $checkoutData['street'],
                'city' => $checkoutData['city'],
                'zip' => $checkoutData['zip'],
                'country' => $checkoutData['country'] ?? 'CZ',
                'is_company' => $checkoutData['isCompany'] ?? false,
                'company_name' => $checkoutData['companyName'] ?? null,
                'company_id' => $checkoutData['companyId'] ?? null,
                'company_vat_id' => $checkoutData['companyVatId'] ?? null,
                'has_delivery_address' => $checkoutData['hasDeliveryAddress'] ?? false,
                'delivery_first_name' => $checkoutData['deliveryFirstName'] ?? null,
                'delivery_last_name' => $checkoutData['deliveryLastName'] ?? null,
                'delivery_street' => $checkoutData['deliveryStreet'] ?? null,
                'delivery_city' => $checkoutData['deliveryCity'] ?? null,
                'delivery_zip' => $checkoutData['deliveryZip'] ?? null,
                'delivery_country' => $checkoutData['deliveryCountry'] ?? null,
                'note' => $checkoutData['note'] ?? null,
                'subtotal' => $subtotal,
                'vat_total' => $vatTotal,
                'total' => $total,
            ]);

            // Vytvoření položek objednávky z košíku
            foreach ($basketItems as $basketItem) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'entity_id' => $basketItem['entity_id'],
                    'model_id' => $basketItem['model_id'],
                    'type' => $basketItem['type'],
                    'price' => $basketItem['price'],
                    'price_total' => $basketItem['price_total'],
                    'vat' => $basketItem['vat'],
                    'quantity' => $basketItem['quantity'],
                ]);
            }

            return $order->load('items');
        });
    }


    /**
     * Vygeneruje unikátní číslo objednávky
     *
     * @return string
     */
    private function generateOrderNumber(): string
    {
        do {
            $orderNumber = 'ORD-' . date('Ymd') . '-' . strtoupper(Str::random(6));
        } while (Order::where('order_number', $orderNumber)->exists());

        return $orderNumber;
    }
}

