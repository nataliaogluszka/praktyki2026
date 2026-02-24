<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Coupon;

class CartController extends Controller
{
    // public function index()
    // {
    //     $cart = session()->get('cart', []);
    //     $total = array_sum(array_map(fn($item) => $item['price'] * $item['quantity'], $cart));

    //     $vatRate = 0.23;
    //     $taxAmount = $total - ($total / (1 + $vatRate));

    //     return view('cart', compact('cart', 'total', 'taxAmount'));
    // }

    public function index()
    {
        $cart = session()->get('cart', []);
        $total = array_sum(array_map(fn($item) => $item['price'] * $item['quantity'], $cart));
        
        // Pobieramy kupon z sesji
        $coupon = session()->get('coupon');
        $discount = 0;

        if ($coupon) {
            if ($coupon['type'] === 'percent') {
                $discount = $total * ($coupon['value'] / 100);
            } else {
                $discount = $coupon['value'];
            }
        }

        $totalAfterDiscount = max(0, $total - $discount); // Cena nie może być ujemna
        
        $vatRate = 0.23;
        $taxAmount = $totalAfterDiscount - ($totalAfterDiscount / (1 + $vatRate));

        return view('cart', compact('cart', 'total', 'discount', 'totalAfterDiscount', 'taxAmount'));
    }

    public function removeCoupon()
    {
        session()->forget('coupon');
        return redirect()->back()->with('success', 'Kod rabatowy został usunięty.');
    }
    
    public function show(string $name)
    {
        //
    }

    public function applyCoupon(Request $request)
    {
        $coupon = Coupon::where('code', $request->code)->first();

        if (!$coupon) {
            return redirect()->back()->with('error', 'Nieprawidłowy kod rabatowy.');
        }

        session()->put('coupon', [
            'code' => $coupon->code,
            'type' => $coupon->type,
            'value' => $coupon->value
        ]);

        return redirect()->back()->with('success', 'Kupon został naliczony!');
    }

    public function add(Request $request, $id)
    {
        $product = Product::findOrFail($id);
        $cart = session()->get('cart', []);

        if(isset($cart[$id])) {
            $cart[$id]['quantity']++;
        } else {
            $cart[$id] = [
                "name" => $product->name,
                "quantity" => 1,
                "price" => $product->price,
                "image" => $product->image
            ];
        }

        session()->put('cart', $cart);
        
        return redirect()->back()->with('success', 'Produkt dodany do koszyka!');
    }

    public function remove($id)
    {
        $cart = session()->get('cart');

        if(isset($cart[$id])) {
            unset($cart[$id]);
            session()->put('cart', $cart);
        }

        return redirect()->back()->with('success', 'Produkt został usunięty z koszyka.');
    }

    public function update(Request $request, $id)
    {
        $cart = session()->get('cart');

        if(isset($cart[$id])) {
            if($request->action == 'increase') {
                $cart[$id]['quantity']++;
            } elseif($request->action == 'decrease' && $cart[$id]['quantity'] > 1) {
                $cart[$id]['quantity']--;
            }
            
            session()->put('cart', $cart);
        }

        return redirect()->back()->with('success', 'Zaktualizowano ilość produktów.');
    }
}