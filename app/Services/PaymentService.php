<?php

namespace App\Services;

use App\Models\Payment;
use Illuminate\Support\Facades\DB;

class PaymentService
{
    /**
     * Získá všechny platby seřazené podle pořadí
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getAll()
    {
        return Payment::ordered()->get();
    }

    /**
     * Vytvoří novou platbu
     *
     * @param array $data
     * @return Payment
     */
    public function create(array $data): Payment
    {
        return DB::transaction(function () use ($data) {
            // Zjistit maximální pořadí
            $maxOrder = Payment::max('order') ?? 0;

            $payment = Payment::create([
                'name' => $data['name'],
                'description' => $data['description'] ?? null,
                'price' => $data['price'] ?? 0,
                'order' => $data['order'] ?? ($maxOrder + 1),
                'active' => $data['active'] ?? true,
                'type' => $data['type'],
            ]);

            return $payment;
        });
    }

    /**
     * Aktualizuje platbu
     *
     * @param Payment $payment
     * @param array $data
     * @return Payment
     */
    public function update(Payment $payment, array $data): Payment
    {
        return DB::transaction(function () use ($payment, $data) {
            $payment->update([
                'name' => $data['name'],
                'description' => $data['description'] ?? null,
                'price' => $data['price'] ?? 0,
                'order' => $data['order'] ?? $payment->order,
                'active' => $data['active'] ?? $payment->active,
                'type' => $data['type'] ?? $payment->type,
            ]);

            return $payment->fresh();
        });
    }

    /**
     * Smaže platbu
     *
     * @param Payment $payment
     * @return bool
     */
    public function delete(Payment $payment): bool
    {
        return DB::transaction(function () use ($payment) {
            // Shipping záznamy se smažou automaticky díky cascade
            return $payment->delete();
        });
    }

    /**
     * Aktualizuje pořadí plateb
     *
     * @param array $items Array of ['id' => int, 'order' => int]
     * @return void
     */
    public function updateOrder(array $items): void
    {
        DB::transaction(function () use ($items) {
            foreach ($items as $item) {
                Payment::where('id', $item['id'])->update([
                    'order' => $item['order']
                ]);
            }
        });
    }
}
