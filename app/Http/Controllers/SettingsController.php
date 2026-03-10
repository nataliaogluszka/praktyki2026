<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ShippingMethod;
use App\Models\EmailTemplate;
use App\Models\Setting;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;

class SettingsController extends Controller
{
    public function index()
    {
        $shippingMethods = ShippingMethod::all();
        $emailTemplates = EmailTemplate::all();

        $settings = Setting::pluck('value', 'key')->toArray();

        return view('settings.index', compact('shippingMethods', 'settings', 'emailTemplates'));
    }

    public function update(Request $request)
    {
        if ($request->hasFile('shop_logo')) {
            $request->validate([
                'shop_logo' => 'required|image|mimes:png|max:2048',
            ]);

            $path = public_path('images/logo');

            if (!File::isDirectory($path)) {
                File::makeDirectory($path, 0777, true, true);
            }

            $request->file('shop_logo')->move($path, 'logo.png');
            
            Setting::updateOrCreate(
                ['key' => 'shop_logo'], 
                ['value' => 'images/logo/logo.png']
            );
        }

        if ($request->has('shipping')) {
            foreach ($request->shipping as $id => $price) {
                ShippingMethod::where('id', $id)->update(['price' => $price]);
            }
        }
        $inputs = $request->except(['_token', '_method', 'shipping', 'shop_logo']);

        foreach ($inputs as $key => $value) {
            Setting::updateOrCreate(
                ['key' => $key],
                ['value' => $value ?? ''] 
            );
        }

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