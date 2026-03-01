<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Coupon;
use App\Models\Category;

class CouponController extends Controller
{
    public function index(Request $request)
    {
        $coupons = Coupon::paginate(10);
        $categories = Category::whereNull('parent_id')->with('children')->get();      
        $coupons = Coupon::with('category')->get();          
    
        return view('coupons.index', compact('coupons', 'categories'));
    }

    public function store(Request $request) {
        $data = $request->validate([
            'code' => 'required|unique:coupons,code',
            'type' => 'required|in:fixed,percent',
            'value' => 'required|numeric|min:0',
            'min_cart_value' => 'nullable|numeric|min:0',
            'starts_at' => 'nullable|date',
            'expires_at' => 'nullable|date|after_or_equal:starts_at',
            'usage_limit' => 'nullable|integer|min:1',
            'category_id' => 'nullable|exists:categories,id',
        ]);

        $data['is_active'] = $request->has('is_active') ? 1 : 0;

        if (empty($data['category_id'])) {
            $data['category_id'] = null;
        }

        Coupon::create($data);
        return back()->with('success', 'Kupon dodany pomyślnie!');
    }

    public function update(Request $request, Coupon $coupon) {
        $data = $request->validate([
            'code' => 'required|unique:coupons,code,' . $coupon->id,
            'type' => 'required|in:fixed,percent',
            'value' => 'required|numeric|min:0',
            'min_cart_value' => 'nullable|numeric|min:0',
            'starts_at' => 'nullable|date',
            'expires_at' => 'nullable|date|after_or_equal:starts_at',
            'usage_limit' => 'nullable|integer|min:1',
            'category_id' => 'nullable|exists:categories,id',
        ]);

        $data['is_active'] = $request->has('is_active') ? 1 : 0;

        if (empty($data['category_id'])) {
            $data['category_id'] = null;
        }

        $coupon->update($data);
        return back()->with('success', 'Kupon zaktualizowany!');
    }

    public function resetUsage(Coupon $coupon)
    {
        $coupon->update(['used_count' => 0]);
        return back()->with('success', 'Licznik użyć kuponu został wyczyszczony.');
    }

    public function destroy(Coupon $coupon)
    {
        $coupon->delete();
        return redirect()->back()->with('success', 'Kod został usunięty.');
    }
}
