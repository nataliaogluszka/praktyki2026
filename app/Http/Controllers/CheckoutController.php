<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\ShippingMethod;
use App\Models\Setting;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CheckoutController extends Controller
{
    public function index()
    {
        $cart = session()->get('cart', []);
        if (empty($cart)) return redirect()->route('cart.index');

        $shippingMethods = ShippingMethod::all();
        $cartTotal = array_sum(array_map(fn($item) => $item['price'] * $item['quantity'], $cart));
        
        $coupon = session()->get('coupon');
        $discount = 0;

        if ($coupon) {
            $discount = ($coupon['type'] === 'percent') 
                ? $cartTotal * ($coupon['value'] / 100) 
                : $coupon['value'];
        }

        $total = max(0, $cartTotal - $discount);
        $addresses = Auth::check() ? Auth::user()->addresses : collect();

        return view('checkout.index', compact('cart', 'total', 'discount', 'addresses', 'shippingMethods'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'surname' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|min:9|max:20',

            'shipping_street' => 'required|string|max:255',
            'shipping_number' => 'required|string|max:20',
            'shipping_postcode' => 'required|string|max:10',
            'shipping_city' => 'required|string|max:255',

            'shipping_method_id' => 'required|exists:shipping_methods,id',
            'payment_method' => 'required|in:online,cod',
        ]);

        $shippingMethod = ShippingMethod::findOrFail($request->shipping_method_id);
        $vatRateSetting = Setting::where('key', 'vat_rate')->first();
        $vatRate = $vatRateSetting ? (float)$vatRateSetting->value : 23.0;

        $cart = session()->get('cart', []);
        $cartTotal = array_sum(array_map(fn($item) => $item['price'] * $item['quantity'], $cart));
        
        $coupon = session()->get('coupon');
        $discountAmount = 0;
        if ($coupon) {
            $discountAmount = ($coupon['type'] === 'percent') 
                ? $cartTotal * ($coupon['value'] / 100) 
                : $coupon['value'];
        }

        $finalTotal = max(0, $cartTotal - $discountAmount) + $shippingMethod->price;
        $taxAmount = $finalTotal * ($vatRate / (100 + $vatRate));

        DB::beginTransaction();
        try {
            $address = $this->formatAddress($request);

            $order = Order::create([
                'user_id'            => Auth::id(),
                'total_price'        => $finalTotal,
                'tax_amount'         => $taxAmount,
                'shipping_method_id' => $shippingMethod->id, 
                'shipping_method'    => $shippingMethod->name,
                'status'             => 'przyjęte',
                'is_paid'            => false,
                'is_completed'       => false,
                'shipping_address'   => $address,
                'billing_address'    => $address,
            ]);

            foreach ($cart as $id => $item) {
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
            session()->forget(['cart', 'coupon']);
            return view('checkout.thanks', compact('order'));

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Błąd podczas składania zamówienia: ' . $e->getMessage());
        }
    }

    private function formatAddress($request)
    {
        return "{$request->name} {$request->surname}\n" .
               "{$request->shipping_street} {$request->shipping_number}\n" .
               "{$request->shipping_postcode} {$request->shipping_city}\n" .
               "Tel: {$request->phone}";
    }
}