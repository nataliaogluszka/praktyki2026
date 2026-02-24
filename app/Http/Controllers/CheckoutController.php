<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CheckoutController extends Controller{

public function index()
{
    $cart = session()->get('cart', []);
    if(empty($cart)) return redirect()->route('cart.index');

    // Podstawowa suma koszyka
    $cartTotal = array_sum(array_map(fn($item) => $item['price'] * $item['quantity'], $cart));
    
    // Logika rabatu
    $coupon = session()->get('coupon');
    $discount = 0;

    if ($coupon) {
        if ($coupon['type'] === 'percent') {
            $discount = $cartTotal * ($coupon['value'] / 100);
        } else {
            $discount = $coupon['value'];
        }
    }

    $total = max(0, $cartTotal - $discount); // Suma po rabacie (bez dostawy)

    $addresses = Auth::check() 
        ? Auth::user()->addresses 
        : collect();

    return view('checkout.index', compact('cart', 'total', 'discount', 'addresses'));
}

public function store(Request $request)
{
    // ... (1. Walidacja pozostaje bez zmian)
    $request->validate([/* Twoje reguły */]);

    $cart = session()->get('cart', []);
    if (empty($cart)) return redirect()->route('cart.index');

    // 2. Koszty dostawy
    $shippingRates = [
        'paczkomat' => 15.00,
        'kurier_inpost' => 19.00,
        'dhl' => 22.00
    ];
    $shippingCost = $shippingRates[$request->shipping_method] ?? 15.00;
    
    // Obliczenie sumy koszyka
    $cartTotal = array_sum(array_map(fn($item) => $item['price'] * $item['quantity'], $cart));

    // --- NOWA LOGIKA RABATOWA ---
    $coupon = session()->get('coupon');
    $discountAmount = 0;

    if ($coupon) {
        if ($coupon['type'] === 'percent') {
            $discountAmount = $cartTotal * ($coupon['value'] / 100);
        } else {
            $discountAmount = $coupon['value'];
        }
    }

    // Finalna cena: (Suma produktów - Rabat) + Dostawa
    $finalTotal = max(0, $cartTotal - $discountAmount) + $shippingCost;
    // ----------------------------

    DB::beginTransaction();
    try {
        // 3. Tworzenie Zamówienia
        $order = Order::create([
            'user_id' => Auth::id(),
            'total_price' => $finalTotal,
            'tax_amount' => $finalTotal * 0.23 / 1.23,
            'shipping_method' => $request->shipping_method,
            'status' => 'przyjęte',
            'is_paid' => false,
            'is_completed' => false,
            'shipping_address' => "{$request->name} {$request->surname}\n{$request->shipping_street}\n{$request->shipping_postcode} {$request->shipping_city}\nTel: {$request->phone}",
            'billing_address' => "{$request->name} {$request->surname}\n{$request->shipping_street}\n{$request->shipping_postcode} {$request->shipping_city}",
            // Opcjonalnie: możesz dodać kolumnę 'discount_amount' do tabeli orders, jeśli ją masz
            // 'discount_amount' => $discountAmount, 
        ]);

        // ... (4. Reszta kodu: Zapis adresu, Magazyn i OrderItem pozostaje bez zmian)

        DB::commit();
        
        // Czyścimy koszyk I kupon po udanym zamówieniu
        session()->forget(['cart', 'coupon']);

        return view('checkout.thanks', compact('order'));

    } catch (\Exception $e) {
        DB::rollBack();
        return redirect()->route('cart.index')->with('error', $e->getMessage());
    }
}

    /**
     * Pomocnicza funkcja do formatowania adresu w jeden ciąg tekstowy
     */
    private function formatAddress($request)
    {
        return "{$request->shipping_name}\n{$request->shipping_street}\n{$request->shipping_postcode} {$request->shipping_city}\nTel: {$request->phone}";
    }
}