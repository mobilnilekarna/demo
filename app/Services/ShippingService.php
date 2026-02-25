<?php

namespace App\Services;

use App\Models\Payment;
use App\Models\Shipping;
use App\Models\Transport;
use Illuminate\Support\Collection;

class ShippingService
{
    /**
     * Získá transports s dostupnými platbami pro checkout
     *
     * @return Collection
     */
    public function getTransportsWithPayments(): Collection
    {
        // Načíst aktivní transports
        $transports = Transport::active()
            ->ordered()
            ->get();

        // Načíst aktivní payments
        $payments = Payment::active()
            ->ordered()
            ->get()
            ->keyBy('id');

        // Načíst všechny kombinace shippings s vztahy
        // Order musí být řazeno per transport_id, takže řadíme až po groupBy
        $shippings = Shipping::with(['transport', 'payment'])
            ->get()
            ->groupBy('transport_id');

        // Připravit strukturovaná data pro transports s dostupnými platbami
        return $transports->map(function ($transport) use ($payments, $shippings) {
            // Získat dostupné platby pro tento transport, seřazené podle order
            // Order je per transport_id, takže každá skupina má vlastní order 0, 1, 2...
            $availableShippings = $shippings->get($transport->id, collect())
                ->sortBy('order')
                ->values();

            $availablePayments = $availableShippings->map(function ($shipping) use ($payments) {
                $payment = $payments->get($shipping->payment_id);
                if (!$payment) {
                    return null;
                }

                return [
                    'id' => $payment->id,
                    'name' => $payment->name,
                    'description' => $payment->description,
                    'price' => (float) $payment->price,
                    'type' => $payment->type,
                    'image' => $payment->image,
                    'shipping_id' => $shipping->id,
                    'shipping_price' => $shipping->price ? (float) $shipping->price : null,
                    'final_price' => (float) $shipping->final_price,
                ];
            })->filter()->values();

            return [
                'id' => $transport->id,
                'name' => $transport->name,
                'description' => $transport->description,
                'price' => (float) $transport->price,
                'free_from' => $transport->free_from ? (float) $transport->free_from : null,
                'type' => $transport->type,
                'type_delivery' => $transport->type_delivery,
                'image' => $transport->image,
                'available_payments' => $availablePayments,
            ];
        });
    }
}

