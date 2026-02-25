<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ShippingMethod;
use App\Models\Setting;
use Illuminate\Support\Str;

class SettingsController extends Controller
{
    public function index()
    {
        $shippingMethods = ShippingMethod::all();
        $vatRate = Setting::where('key', 'vat_rate')->first()->value ?? 23;
        return view('settings.index', compact('shippingMethods', 'vatRate'));
    }

    public function update(Request $request)
    {
        // Aktualizacja cen wysyłki
        foreach ($request->shipping as $id => $price) {
            ShippingMethod::where('id', $id)->update(['price' => $price]);
        }

        // Aktualizacja VAT
        Setting::where('key', 'vat_rate')->update(['value' => $request->vat_rate]);

        return back()->with('success', 'Ustawienia zostały zapisane.');
    }

    public function store(Request $request)
{
    $request->validate([
        'name' => 'required|string|max:255',
        'price' => 'required|numeric|min:0',
    ]);

    ShippingMethod::create([
        'name' => $request->name,
        'slug' => Str::slug($request->name),
        'price' => $request->price,
    ]);

    return back()->with('success', 'Nowa metoda dostawy została dodana.');
}

public function destroy($id)
{
    $method = ShippingMethod::findOrFail($id);
    $method->delete();

    return back()->with('success', 'Metoda dostawy została usunięta.');
}
}