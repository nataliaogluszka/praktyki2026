<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use App\Models\Coupon;

class CouponController extends Controller
{
    public function index(Request $request)
    {
        $coupons = Coupon::paginate(10);
        return view('coupons.index', compact('coupons'));
    }
    
    public function store(Request $request)
    {
        $validated = $request->validate([
            'code' => 'required|unique:coupons,code|max:255',
            'type' => 'required|in:fixed,percent',
            'value' => 'required|numeric|min:0',
        ]);

        Coupon::create($validated);

        return redirect()->back()->with('success', 'Kod rabatowy został dodany.');
    }

    public function update(Request $request, Coupon $coupon)
    {
        $validated = $request->validate([
            'code' => 'required|max:255|unique:coupons,code,' . $coupon->id,
            'type' => 'required|in:fixed,percent',
            'value' => 'required|numeric|min:0',
        ]);

        $coupon->update($validated);

        return redirect()->route('coupons.index')->with('success', 'Kod został zaktualizowany.');
    }

    public function destroy(Coupon $coupon)
    {
        $coupon->delete();
        return redirect()->back()->with('success', 'Kod został usunięty.');
    }
}
