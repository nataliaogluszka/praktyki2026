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
        if(empty($cart)) return redirect()->route('home');

        $total = array_sum(array_map(fn($item) => $item['price'] * $item['quantity'], $cart));
        
        return view('checkout.index', compact('cart', 'total'));
    }

    public function store(Request $request)
{
    // 1. Walidacja (dostosowana do Twoich pól z formularza)
    $request->validate([
        'name' => 'required|string|max:255',
        'surname' => 'required|string|max:255',
        'email' => 'required|email',
        'phone' => 'required|string|max:20',
        'shipping_street' => 'required|string',
        'shipping_postcode' => 'required|string|max:10', // w formularzu masz shipping_postcode
        'shipping_city' => 'required|string',
        'shipping_method' => 'required',
        'payment_method' => 'required'
    ]);

    $cart = session()->get('cart', []);
    if (empty($cart)) return redirect()->route('cart.index');

    // 2. Koszty dostawy
    $shippingRates = [
        'paczkomat' => 15.00,
        'kurier_inpost' => 19.00,
        'dhl' => 22.00
    ];
    $shippingCost = $shippingRates[$request->shipping_method] ?? 15.00;
    
    $cartTotal = array_sum(array_map(fn($item) => $item['price'] * $item['quantity'], $cart));
    $finalTotal = $cartTotal + $shippingCost;

    DB::beginTransaction();
    try {
        // 3. Tworzenie Zamówienia
        $order = Order::create([
            'user_id' => Auth::id(),
            'total_price' => $finalTotal,
            'tax_amount' => $finalTotal * 0.23 / 1.23,
            'shipping_method' => $request->shipping_method,
            'status' => 'pending',
            'is_paid' => false,
            'is_completed' => false,
            // Formatujemy adres do jednej kolumny tekstowej w orders
            'shipping_address' => "{$request->name} {$request->surname}\n{$request->shipping_street}\n{$request->shipping_postcode} {$request->shipping_city}\nTel: {$request->phone}",
            'billing_address' => "{$request->name} {$request->surname}\n{$request->shipping_street}\n{$request->shipping_postcode} {$request->shipping_city}",
        ]);

        // 4. Zapis do Twojej tabeli 'addresses' (image_d798c0.png)
        if ($request->has('save_address') && Auth::check()) {
            DB::table('addresses')->updateOrInsert(
                ['user_id' => Auth::id()],
                [
                    'street' => $request->shipping_street,
                    'house_number' => $request->shipping_number ?? '',
                    'city' => $request->shipping_city,
                    'postal_code' => $request->shipping_postcode, // Uwaga: w tabeli masz postal_code
                    'country' => 'Polska',
                    'updated_at' => now(),
                    'created_at' => now()
                ]
            );
        }

        // 5. Zapis pozycji zamówienia
        foreach ($cart as $id => $item) {
            $product = \App\Models\Product::findOrFail($id);

            // --- DODANA LOGIKA MAGAZYNOWA ---
            // 1. Sprawdź, czy mamy dość towaru
            if (!$product->inventory || $product->inventory->quantity < $item['quantity']) {
                throw new \Exception("Produkt '{$item['name']}' nie jest już dostępny w wybranej ilości.");
            }

            // 2. Odejmij stan (używamy decrement dla bezpieczeństwa)
            $product->inventory()->decrement('quantity', $item['quantity']);
            // --------------------------------


            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $id,
                'product_name' => $item['name'],
                'quantity' => $item['quantity'],
                'unit_price_gross' => $item['price'],
                'tax_rate' => 23.00,
            ]);
        }

        DB::commit();
        session()->forget('cart');

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