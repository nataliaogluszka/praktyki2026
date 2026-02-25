<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use Illuminate\Contracts\View\View;

class InventoryController extends Controller
{
    public function index(Request $request): View
    {
        $query = Product::with('inventory');

        
        if ($request->has('status')) {
            $query->whereHas('inventory', function ($q) use ($request) {
                if ($request->status == 'brak') {
                    $q->where('quantity', 0);
                } elseif ($request->status == 'ponizej20') {
                    $q->where('quantity', '<=', 20);
                } elseif ($request->status == 'ponizej50') {
                    $q->where('quantity', '<=', 50);
                } elseif ($request->status == 'powyzej50') {
                    $q->where('quantity', '>', 50);
                }
            });
        }

        if ($request->has('sort')) {
            if ($request->sort == 'latest') {
                $query->latest();
            } elseif ($request->sort == 'oldest') {
                $query->oldest();
            } elseif ($request->sort == 'majniej') {
                $query->join('inventories', 'products.id', '=', 'inventories.product_id')
                    ->orderBy('inventories.quantity', 'asc')
                    ->select('products.*');
            } elseif ($request->sort == 'najwiecej') {
                $query->join('inventories', 'products.id', '=', 'inventories.product_id')
                    ->orderBy('inventories.quantity', 'desc')
                    ->select('products.*');
            }
        }

        $products = $query->paginate(8)->withQueryString();

        return view('inventories.index', [
            'products' => $products
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
