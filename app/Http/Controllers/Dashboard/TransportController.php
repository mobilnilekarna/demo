<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Transport;
use App\Models\Payment;
use App\Services\TransportService;
use App\Enums\TransportType;
use App\Enums\DeliveryType;
use Illuminate\Http\Request;
use Inertia\Inertia;

class TransportController extends Controller
{
    protected $transportService;

    public function __construct(TransportService $transportService)
    {
        $this->transportService = $transportService;
        $this->middleware('permission:view-transports', ['only' => ['index']]);
        $this->middleware('permission:create-transports', ['only' => ['store']]);
        $this->middleware('permission:edit-transports', ['only' => ['update', 'updatePayments', 'updateOrder']]);
        $this->middleware('permission:delete-transports', ['only' => ['destroy']]);
    }

    /**
     * Zobrazí seznam doprav
     */
    public function index()
    {
        $this->authorize('view-transports');

        $transports = $this->transportService->getAll()->map(function ($transport) {
            return [
                'id' => $transport->id,
                'name' => $transport->name,
                'type' => $transport->type,
                'type_delivery' => $transport->type_delivery,
                'price' => (float) $transport->price,
                'free_from' => $transport->free_from ? (float) $transport->free_from : null,
                'order' => $transport->order,
                'active' => $transport->active,
                'image' => TransportType::tryFrom($transport->type)->image(),
                'created_at' => $transport->created_at->format('d.m.Y H:i'),
            ];
        });

        return Inertia::render('Dashboard/Transports/List', [
            'transports' => $transports,
        ]);
    }

    /**
     * Vytvoří novou dopravu
     */
    public function store(Request $request)
    {
        $this->authorize('create-transports');

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'free_from' => 'nullable|numeric|min:0',
            'order' => 'nullable|integer|min:0',
            'active' => 'boolean',
            'type' => 'required|string',
            'type_delivery' => 'nullable|string',
        ]);

        $transport = $this->transportService->create($validated);

        return redirect()->route('dashboard.transports.index')
            ->with('success', 'Doprava byla úspěšně vytvořena.');
    }

    /**
     * Aktualizuje dopravu
     */
    public function update(Request $request, Transport $transport)
    {
        $this->authorize('edit-transports');

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'free_from' => 'nullable|numeric|min:0',
            'order' => 'nullable|integer|min:0',
            'active' => 'boolean',
            'type' => 'required|string',
            'type_delivery' => 'nullable|string',
        ]);

        $this->transportService->update($transport, $validated);

        return redirect()->route('dashboard.transports.index')
            ->with('success', 'Doprava byla úspěšně aktualizována.');
    }

    /**
     * Smaže dopravu
     */
    public function destroy(Transport $transport)
    {
        $this->authorize('delete-transports');

        $this->transportService->delete($transport);

        return redirect()->route('dashboard.transports.index')
            ->with('success', 'Doprava byla úspěšně smazána.');
    }

    /**
     * Získá dopravu s přiřazenými platbami pro editaci
     */
    public function edit(Transport $transport)
    {
        $this->authorize('edit-transports');

        $transport = $this->transportService->getWithPayments($transport);

        // Získat všechny platby pro výběr
        $allPayments = Payment::ordered()->get()->map(function ($payment) {
            return [
                'id' => $payment->id,
                'name' => $payment->name,
                'type' => $payment->type,
                'price' => (float) $payment->price,
                'active' => $payment->active,
            ];
        });

        // Načíst přiřazené platby seřazené podle pořadí
        $assignedPayments = $transport->shippings->map(function ($shipping) {
            return [
                'payment_id' => $shipping->payment_id,
                'payment_name' => $shipping->payment->name,
                'payment_type' => $shipping->payment->type,
                'price' => $shipping->price ? (float) $shipping->price : null,
                'order' => $shipping->order,
            ];
        })->sortBy('order')->values();

        $transportTypes = collect(TransportType::cases())->map(function ($type) {
            return [
                'value' => $type->value,
                'label' => $type->name(),
            ];
        });

        $deliveryTypes = collect(DeliveryType::cases())->map(function ($type) {
            return [
                'value' => $type->value,
                'label' => $type->name(),
            ];
        });

        return Inertia::render('Dashboard/Transports/Edit', [
            'transport' => [
                'id' => $transport->id,
                'name' => $transport->name,
                'description' => $transport->description,
                'type' => $transport->type,
                'type_delivery' => $transport->type_delivery,
                'price' => (float) $transport->price,
                'free_from' => $transport->free_from ? (float) $transport->free_from : null,
                'order' => $transport->order,
                'active' => $transport->active,
            ],
            'allPayments' => $allPayments,
            'assignedPayments' => $assignedPayments,
            'transportTypes' => $transportTypes,
            'deliveryTypes' => $deliveryTypes,
        ]);
    }

    /**
     * Aktualizuje přiřazené platby k dopravě
     */
    public function updatePayments(Request $request, Transport $transport)
    {
        $this->authorize('edit-transports');

        $validated = $request->validate([
            'payments' => 'required|array',
            'payments.*.payment_id' => 'required|exists:payments,id',
            'payments.*.price' => 'nullable|numeric|min:0',
            'payments.*.order' => 'required|integer|min:0',
        ]);

        $this->transportService->updatePayments($transport, $validated['payments']);

        return redirect()->route('dashboard.transports.edit', $transport)
            ->with('success', 'Platby byly úspěšně aktualizovány.');
    }

    /**
     * Aktualizuje pořadí doprav
     */
    public function updateOrder(Request $request)
    {
        $this->authorize('edit-transports');

        $validated = $request->validate([
            'items' => 'required|array',
            'items.*.id' => 'required|exists:transports,id',
            'items.*.order' => 'required|integer|min:0',
        ]);

        $this->transportService->updateOrder($validated['items']);

        return redirect()->route('dashboard.transports.index')
            ->with('success', 'Pořadí doprav bylo úspěšně aktualizováno.');
    }
}
