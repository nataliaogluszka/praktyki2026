<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use Illuminate\Contracts\View\View;

class InventoryController extends Controller
{
    public function index(Request $request): View
    {
        return view('inventories.index', [
            // Pobieramy produkty razem z ich stanami magazynowymi
            'products' => Product::with('inventory')->paginate(8)
        ]);
    }
    
    public function show(string $id)
    {
        //
    }

    public function edit(Product $product)
    {
        //
    }

    public function update(Request $request)
{
    $request->validate([
        'id' => 'required|exists:products,id',
        'quantity' => 'required|integer|min:0',
    ]);

    // Aktualizujemy rekord w powiązanej tabeli inventory
    $product = Product::findOrFail($request->id);
    
    // updateOrCreate zabezpieczy Cię na wypadek, gdyby rekord w inventories jeszcze nie istniał
    $product->inventory()->updateOrCreate(
        ['product_id' => $product->id],
        ['quantity' => $request->quantity]
    );

    return redirect()->back()->with('success', 'Stan zaktualizowany!');
}

    public function store(Request $request) {
        //   
    }
}
