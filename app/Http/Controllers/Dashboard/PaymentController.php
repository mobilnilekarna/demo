<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Services\PaymentService;
use App\Enums\PaymentType;
use Illuminate\Http\Request;
use Inertia\Inertia;

class PaymentController extends Controller
{
    protected $paymentService;

    public function __construct(PaymentService $paymentService)
    {
        $this->paymentService = $paymentService;
        $this->middleware('permission:view-payments', ['only' => ['index']]);
        $this->middleware('permission:create-payments', ['only' => ['store']]);
        $this->middleware('permission:edit-payments', ['only' => ['update', 'updateOrder']]);
        $this->middleware('permission:delete-payments', ['only' => ['destroy']]);
    }

    /**
     * Zobrazí seznam plateb
     */
    public function index()
    {
        $this->authorize('view-payments');

        $payments = $this->paymentService->getAll()->map(function ($payment) {
            return [
                'id' => $payment->id,
                'name' => $payment->name,
                'type' => $payment->type,
                'price' => (float) $payment->price,
                'order' => $payment->order,
                'active' => $payment->active,
                'created_at' => $payment->created_at->format('d.m.Y H:i'),
            ];
        });

        $paymentTypes = collect(PaymentType::cases())->map(function ($type) {
            return [
                'value' => $type->value,
                'label' => $type->name(),
            ];
        });

        return Inertia::render('Dashboard/Payments/List', [
            'payments' => $payments,
            'paymentTypes' => $paymentTypes,
        ]);
    }

    /**
     * Vytvoří novou platbu
     */
    public function store(Request $request)
    {
        $this->authorize('create-payments');

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'order' => 'nullable|integer|min:0',
            'active' => 'boolean',
            'type' => 'required|string',
        ]);

        $payment = $this->paymentService->create($validated);

        return redirect()->route('dashboard.payments.index')
            ->with('success', 'Platba byla úspěšně vytvořena.');
    }

    /**
     * Aktualizuje platbu
     */
    public function update(Request $request, Payment $payment)
    {
        $this->authorize('edit-payments');

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'order' => 'nullable|integer|min:0',
            'active' => 'boolean',
            'type' => 'required|string',
        ]);

        $this->paymentService->update($payment, $validated);

        return redirect()->route('dashboard.payments.index')
            ->with('success', 'Platba byla úspěšně aktualizována.');
    }

    /**
     * Smaže platbu
     */
    public function destroy(Payment $payment)
    {
        $this->authorize('delete-payments');

        $this->paymentService->delete($payment);

        return redirect()->route('dashboard.payments.index')
            ->with('success', 'Platba byla úspěšně smazána.');
    }

    /**
     * Aktualizuje pořadí plateb
     */
    public function updateOrder(Request $request)
    {
        $this->authorize('edit-payments');

        $validated = $request->validate([
            'items' => 'required|array',
            'items.*.id' => 'required|exists:payments,id',
            'items.*.order' => 'required|integer|min:0',
        ]);

        $this->paymentService->updateOrder($validated['items']);

        return redirect()->route('dashboard.payments.index')
            ->with('success', 'Pořadí plateb bylo úspěšně aktualizováno.');
    }
}
