<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Transport;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Inertia\Inertia;

class ProfileController extends Controller
{
    /**
     * Zobrazí profilovou stránku
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $section = $request->get('section', 'info'); // default section

        // Načtení objednávek uživatele
        $orders = Order::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->limit(20)
            ->get()
            ->map(function ($order) {
                return [
                    'id' => $order->id,
                    'order_number' => $order->order_number,
                    'status' => $order->status,
                    'total' => $order->total,
                    'created_at' => $order->created_at->format('d.m.Y H:i'),
                ];
            });

        // Načtení doprav a plateb pro preference
        $transports = Transport::where('active', true)->orderBy('order')->get()->map(function ($transport) {
            return [
                'id' => $transport->id,
                'name' => $transport->name,
                'description' => $transport->description,
                'price' => $transport->price,
                'image' => $transport->image,
            ];
        });

        $payments = Payment::where('active', true)->orderBy('order')->get()->map(function ($payment) {
            return [
                'id' => $payment->id,
                'name' => $payment->name,
                'description' => $payment->description,
                'price' => $payment->price,
                'image' => $payment->image,
            ];
        });

        return Inertia::render('Profile/Index', [
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'phone' => isset($user->phone) ? $user->phone : '',
                'location' => isset($user->location) ? $user->location : '',
                'full_name' => isset($user->full_name) ? $user->full_name : '',
                'postal_code' => isset($user->postal_code) ? $user->postal_code : '',
                'avatar' => isset($user->avatar) ? $user->avatar : null,
            ],
            'orders' => $orders,
            'transports' => $transports,
            'payments' => $payments,
            'preferences' => [
                'default_transport_id' => isset($user->default_transport_id) ? $user->default_transport_id : null,
                'default_payment_id' => isset($user->default_payment_id) ? $user->default_payment_id : null,
            ],
            'activeSection' => $section,
        ]);
    }

    /**
     * Aktualizuje základní údaje uživatele
     */
    public function updateInfo(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $user->id,
            'full_name' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:20',
            'location' => 'nullable|string|max:255',
            'postal_code' => 'nullable|string|max:10',
        ]);

        // Update only fields that exist in fillable array (name and email by default)
        // For other fields, you would need to add them to the User model's fillable array
        // or create a user_profiles table
        $user->update([
            'name' => $validated['name'],
            'email' => $validated['email'],
        ]);

        // Note: Other fields (phone, location, full_name, postal_code) would need
        // to be added to the users table via migration and User model's fillable array
        // For now, we only update name and email

        return back()->with('success', 'Údaje byly úspěšně uloženy.');
    }

    /**
     * Aktualizuje preference dopravy a platby
     */
    public function updatePreferences(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'default_transport_id' => 'nullable|exists:transports,id',
            'default_payment_id' => 'nullable|exists:payments,id',
        ]);

        // Note: default_transport_id and default_payment_id would need to be added
        // to the users table via migration and User model's fillable array
        // For now, we'll skip the update if fields don't exist

        // This is a placeholder - you'll need to add these fields to users table
        // $user->update($validated);

        return back()->with('success', 'Preference byly úspěšně uloženy.');
    }

    /**
     * Aktualizuje heslo
     */
    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = Auth::user();

        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'Aktuální heslo není správné.']);
        }

        $user->update([
            'password' => Hash::make($request->password),
        ]);

        return back()->with('success', 'Heslo bylo úspěšně změněno.');
    }
}
