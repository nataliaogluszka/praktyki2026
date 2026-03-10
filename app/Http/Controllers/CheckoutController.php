<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\ShippingMethod;
use App\Models\Setting;
use App\Models\Inventory;
use App\Models\Coupon;
use App\Models\Address;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Stripe\Stripe;
use Stripe\Checkout\Session;

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
            'country' => 'required|string|max:255',
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

            if ($request->has('save_address') && Auth::check()) {
                Address::create([
                    'user_id' => Auth::id(),
                    'street' => $request->shipping_street,
                    'house_number' => $request->shipping_number, 
                    'city' => $request->shipping_city,
                    'postal_code' => $request->shipping_postcode, 
                    'country' => $request->country ?? 'Polska',
                ]);
            }

            $order = Order::create([
                'user_id'            => Auth::id(),
                'total_price'        => $finalTotal,
                'tax_amount'         => $taxAmount,
                'shipping_method_id' => $shippingMethod->id, 
                'shipping_method'    => $shippingMethod->name,
                'status'             => $request->payment_method === 'online' ? 'Nieopłacone' : 'przyjęte',
                'is_paid'            => false,
                'is_completed'       => false,
                'shipping_address'   => $address,
                'billing_address'    => $address,
                'currency'           => 'PLN',
            ]);

            foreach ($cart as $inventoryId => $item) {
                $inventory = Inventory::where('id', $inventoryId)->lockForUpdate()->first();
                
                if (!$inventory || $inventory->quantity < $item['quantity']) {
                    throw new \Exception("Produkt {$item['name']} nie jest już dostępny.");
                }

                $inventory->decrement('quantity', $item['quantity']);

                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $inventory->product_id, 
                    'product_name' => $item['name'],
                    'size' => $item['size'], 
                    'quantity' => $item['quantity'],
                    'unit_price_gross' => $item['price'],
                    'tax_rate' => $vatRate,
                ]);
            }

            if ($coupon) {
                $dbCoupon = Coupon::where('code', $coupon['code'])->first();
                if ($dbCoupon) $dbCoupon->increment('used_count');
            }

            if ($request->payment_method === 'online') {
                Stripe::setApiKey(config('services.stripe.secret') ?? env('STRIPE_SECRET'));

                $lineItems = [];
                foreach ($cart as $item) {
                    $lineItems[] = [
                        'price_data' => [
                            'currency' => 'pln',
                            'product_data' => ['name' => $item['name'] . ' (rozmiar: ' . $item['size'] . ')'],
                            'unit_amount' => (int)($item['price'] * 100), 
                        ],
                        'quantity' => $item['quantity'],
                    ];
                }

                if ($shippingMethod->price > 0) {
                    $lineItems[] = [
                        'price_data' => [
                            'currency' => 'pln',
                            'product_data' => ['name' => 'Wysyłka: ' . $shippingMethod->name],
                            'unit_amount' => (int)($shippingMethod->price * 100),
                        ],
                        'quantity' => 1,
                    ];
                }

                if ($discountAmount > 0) {
                    $lineItems[] = [
                        'price_data' => [
                            'currency' => 'pln',
                            'product_data' => ['name' => 'Rabat'],
                            'unit_amount' => -(int)($discountAmount * 100),
                        ],
                        'quantity' => 1,
                    ];
                }

                $checkoutSession = Session::create([
                    'payment_method_types' => ['card', 'p24', 'blik'], 
                    'line_items' => $lineItems,
                    'mode' => 'payment',
                    'success_url' => route('checkout.success') . '?session_id={CHECKOUT_SESSION_ID}&order_id=' . $order->id,
                    'cancel_url' => route('checkout.cancel', ['order_id' => $order->id]),
                ]);

                DB::commit();
                session()->forget(['cart', 'coupon']);
                return redirect($checkoutSession->url);
            }

            DB::commit();
            session()->forget(['cart', 'coupon']);

            try {
                \Illuminate\Support\Facades\Mail::to($request->email)->send(new \App\Mail\OrderConfirmed($order));
            } catch (\Exception $e) {
                \Illuminate\Support\Facades\Log::error("Błąd wysyłki maila: " . $e->getMessage());
            }

            return view('checkout.thanks', compact('order'));

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Błąd: ' . $e->getMessage());
        }
    }

    public function success(Request $request)
    {
        $order = Order::findOrFail($request->order_id);
        
        Stripe::setApiKey(env('STRIPE_SECRET'));
        $session = Session::retrieve($request->session_id);

        if ($session->payment_status === 'paid') {
            $order->update([
                'is_paid' => true,
                'status' => 'Opłacone'
            ]);
        }

        try {
            $customerEmail = $session->customer_details->email ?? $order->user->email ?? null;
            if ($customerEmail) {
                \Illuminate\Support\Facades\Mail::to($customerEmail)->send(new \App\Mail\OrderConfirmed($order));
            }
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error("Błąd wysyłki maila po płatności: " . $e->getMessage());
        }

        return view('checkout.thanks', compact('order'));
    }

    public function cancel(Request $request)
    {
        return redirect()->route('cart.index')->with('error', 'Płatność została anulowana.');
    }

    private function formatAddress($request)
    {
        return "{$request->name} {$request->surname}\n" .
               "{$request->shipping_street} {$request->shipping_number}\n" .
               "{$request->shipping_postcode} {$request->shipping_city}\n" .
               "Tel: {$request->phone}";
    }

    public function repay(Order $order)
    {
        if ($order->is_paid) {
            return redirect()->back()->with('info', 'To zamówienie jest już opłacone.');
        }

        Stripe::setApiKey(config('services.stripe.secret') ?? env('STRIPE_SECRET'));

        $lineItems = [];
        $order->load('orderItems');

        foreach ($order->orderItems as $item) {
            $lineItems[] = [
                'price_data' => [
                    'currency' => 'pln',
                    'product_data' => ['name' => $item->product_name . ' (' . $item->size . ')'],
                    'unit_amount' => (int)($item->unit_price_gross * 100),
                ],
                'quantity' => $item->quantity,
            ];
        }

        $checkoutSession = Session::create([
            'payment_method_types' => ['card', 'p24', 'blik'],
            'line_items' => $lineItems,
            'mode' => 'payment',
            'success_url' => route('checkout.success') . '?session_id={CHECKOUT_SESSION_ID}&order_id=' . $order->id,
            'cancel_url' => route('orders.index'),
        ]);

        return redirect($checkoutSession->url);
    }
}