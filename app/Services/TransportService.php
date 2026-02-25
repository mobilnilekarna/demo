<?php

namespace App\Services;

use App\Models\Transport;
use App\Models\Shipping;
use Illuminate\Support\Facades\DB;

class TransportService
{
    /**
     * Získá všechny dopravy seřazené podle pořadí
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getAll()
    {
        return Transport::ordered()->get();
    }

    /**
     * Vytvoří novou dopravu
     *
     * @param array $data
     * @return Transport
     */
    public function create(array $data): Transport
    {
        return DB::transaction(function () use ($data) {
            // Zjistit maximální pořadí
            $maxOrder = Transport::max('order') ?? 0;

            $transport = Transport::create([
                'name' => $data['name'],
                'description' => $data['description'] ?? null,
                'price' => $data['price'] ?? 0,
                'free_from' => $data['free_from'] ?? null,
                'order' => $data['order'] ?? ($maxOrder + 1),
                'active' => $data['active'] ?? true,
                'type' => $data['type'],
                'type_delivery' => $data['type_delivery'] ?? null,
            ]);

            return $transport;
        });
    }

    /**
     * Aktualizuje dopravu
     *
     * @param Transport $transport
     * @param array $data
     * @return Transport
     */
    public function update(Transport $transport, array $data): Transport
    {
        return DB::transaction(function () use ($transport, $data) {
            $transport->update([
                'name' => $data['name'],
                'description' => $data['description'] ?? null,
                'price' => $data['price'] ?? 0,
                'free_from' => $data['free_from'] ?? null,
                'order' => $data['order'] ?? $transport->order,
                'active' => $data['active'] ?? $transport->active,
                'type' => $data['type'] ?? $transport->type,
                'type_delivery' => $data['type_delivery'] ?? $transport->type_delivery,
            ]);

            return $transport->fresh();
        });
    }

    /**
     * Smaže dopravu
     *
     * @param Transport $transport
     * @return bool
     */
    public function delete(Transport $transport): bool
    {
        return DB::transaction(function () use ($transport) {
            // Shipping záznamy se smažou automaticky díky cascade
            return $transport->delete();
        });
    }

    /**
     * Aktualizuje přiřazené platby k dopravě s cenami a pořadím
     *
     * @param Transport $transport
     * @param array $payments Array of ['payment_id' => int, 'price' => float|null, 'order' => int]
     * @return void
     */
    public function updatePayments(Transport $transport, array $payments): void
    {
        DB::transaction(function () use ($transport, $payments) {
            // Smazat všechny stávající přiřazení
            Shipping::where('transport_id', $transport->id)->delete();

            // Vytvořit nová přiřazení
            foreach ($payments as $paymentData) {
                Shipping::create([
                    'transport_id' => $transport->id,
                    'payment_id' => $paymentData['payment_id'],
                    'price' => $paymentData['price'] ?? null,
                    'order' => $paymentData['order'] ?? 0,
                ]);
            }
        });
    }

    /**
     * Získá dopravu s přiřazenými platbami seřazenými podle pořadí
     *
     * @param Transport $transport
     * @return Transport
     */
    public function getWithPayments(Transport $transport): Transport
    {
        return $transport->load([
            'shippings' => function ($query) {
                $query->orderBy('order');
            },
            'shippings.payment'
        ]);
    }

    /**
     * Aktualizuje pořadí doprav
     *
     * @param array $items Array of ['id' => int, 'order' => int]
     * @return void
     */
    public function updateOrder(array $items): void
    {
        DB::transaction(function () use ($items) {
            foreach ($items as $item) {
                Transport::where('id', $item['id'])->update([
                    'order' => $item['order']
                ]);
            }
        });
    }
}
