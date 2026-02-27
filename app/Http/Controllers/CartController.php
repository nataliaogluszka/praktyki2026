<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Coupon;
use Carbon\Carbon;

class CartController extends Controller
{
    public function index()
    {
        $cart = session()->get('cart', []);
        $total = array_sum(array_map(fn($item) => $item['price'] * $item['quantity'], $cart));
        
        $couponData = session()->get('coupon');
        $discount = 0;

        if ($couponData) {
            $coupon = Coupon::where('code', $couponData['code'])->first();
            
            if (!$coupon || !$this->isCouponValid($coupon, $total)) {
                session()->forget('coupon');
                return redirect()->route('cart.index')->with('error', 'Warunki kuponu przestały być spełniane.');
            }

            if ($coupon->type === 'percent') {
                $discount = $total * ($coupon->value / 100);
            } else {
                $discount = $coupon->value;
            }
        }

        $totalAfterDiscount = max(0, $total - $discount);
        $vatRate = 0.23;
        $taxAmount = $totalAfterDiscount - ($totalAfterDiscount / (1 + $vatRate));

        return view('cart', compact('cart', 'total', 'discount', 'totalAfterDiscount', 'taxAmount'));
    }

    public function applyCoupon(Request $request)
    {
        $coupon = Coupon::where('code', $request->code)->first();
        $cart = session()->get('cart', []);
        $total = array_sum(array_map(fn($item) => $item['price'] * $item['quantity'], $cart));

        if (!$coupon) {
            return redirect()->back()->with('error', 'Nieprawidłowy kod rabatowy.');
        }


        $errorMessage = '';
        $isValid = $this->isCouponValid($coupon, $total, $errorMessage);
        
        if (!$isValid) {
            return redirect()->back()->with('error', $errorMessage);
        }


        session()->put('coupon', [
            'code' => $coupon->code,
            'type' => $coupon->type,
            'value' => $coupon->value
        ]);

        return redirect()->back()->with('success', 'Kupon został naliczony!');
    }


    private function isCouponValid($coupon, $total, &$errorMessage = null)
    {
        if (!$coupon->is_active) {
            $errorMessage = 'Ten kupon jest obecnie nieaktywny.';
            return false;
        }

        if ($coupon->min_cart_value && $total < $coupon->min_cart_value) {
            $errorMessage = "Minimalna wartość koszyka dla tego kodu to {$coupon->min_cart_value} zł.";
            return false;
        }

        if ($coupon->starts_at && Carbon::now()->lt($coupon->starts_at)) {
            $errorMessage = 'Ten kod rabatowy nie jest jeszcze aktywny.';
            return false;
        }

        if ($coupon->expires_at && Carbon::now()->gt($coupon->expires_at)) {
            $errorMessage = 'Ten kod rabatowy wygasł.';
            return false;
        }

        if ($coupon->usage_limit !== null && $coupon->used_count >= $coupon->usage_limit) {
            $errorMessage = 'Limit wykorzystania tego kodu został wyczerpany.';
            return false;
        }

        if ($coupon->category_id) {
            $cart = session()->get('cart', []);
            $foundValidProduct = false;

            foreach ($cart as $id => $item) {
                $product = \App\Models\Product::find($id);
                // Sprawdzamy czy produkt istnieje i czy należy do kategorii kuponu
                if ($product && $product->category_id == $coupon->category_id) {
                    $foundValidProduct = true;
                    break;
                }
            }

            if (!$foundValidProduct) {
                $categoryName = $coupon->category->name ?? 'wybranej kategorii';
                $errorMessage = "Ten kod rabatowy dotyczy tylko produktów z kategorii: {$categoryName}.";
                return false;
            }
        }

        return true;
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

            if (session()->has('coupon')) {
                $total = array_sum(array_map(fn($item) => $item['price'] * $item['quantity'], $cart));
                $coupon = Coupon::where('code', session('coupon')['code'])->first();
                
                if (!$coupon || !$this->isCouponValid($coupon, $total)) {
                    session()->forget('coupon');
                }
            }
        }

        return redirect()->back()->with('success', 'Zaktualizowano ilość produktów.');
    }

    public function removeCoupon()
    {
        session()->forget('coupon');
        return redirect()->back()->with('success', 'Kod rabatowy został usunięty.');
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

            if (session()->has('coupon')) {
                $total = array_sum(array_map(fn($item) => $item['price'] * $item['quantity'], $cart));
                $coupon = Coupon::where('code', session('coupon')['code'])->first();
                if (!$coupon || !$this->isCouponValid($coupon, $total)) {
                    session()->forget('coupon');
                }
            }
        }

        return redirect()->back()->with('success', 'Produkt został usunięty z koszyka.');
    }

} 