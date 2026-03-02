<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Inventory;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\DB;

class InventoryController extends Controller
{
    public function index(Request $request): View
    {
        $query = Product::with('inventories');

        if ($request->has('status') && $request->status != '') {
            $query->whereHas('inventories', function ($q) use ($request) {
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
            } elseif (in_array($request->sort, ['majniej', 'najwiecej'])) {
                $direction = $request->sort == 'majniej' ? 'asc' : 'desc';
                
                $query->leftJoin('inventories', 'products.id', '=', 'inventories.product_id')
                    ->select('products.*', DB::raw('SUM(inventories.quantity) as total_qty'))
                    ->groupBy('products.id')
                    ->orderBy('total_qty', $direction);
            }
        }

        $products = $query->paginate(8)->withQueryString();

        return view('inventories.index', [
            'products' => $products
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'size' => 'required|string|max:50',
            'quantity' => 'required|integer|min:0',
        ]);

        
        $inventory = Inventory::where('product_id', $request->product_id)
            ->where('size', $request->size)
            ->first();

        if ($inventory) {
            $inventory->increment('quantity', $request->quantity);
        } else {
            Inventory::create([
                'product_id' => $request->product_id,
                'size' => $request->size,
                'quantity' => $request->quantity
            ]);
        }

        return redirect()->back()->with('success', 'Rozmiar został dodany!');
    }

    public function update(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:inventories,id',
            'quantity' => 'required|integer|min:0',
        ]);

        $inventory = Inventory::findOrFail($request->id);
        $inventory->update([
            'quantity' => $request->quantity
        ]);

        return redirect()->back()->with('success', 'Stan magazynowy został zaktualizowany!');
    }

    public function destroy($id)
{
    $inventory = Inventory::findOrFail($id);
    $inventory->delete();

    return redirect()->back()->with('success', 'Rozmiar został usunięty z magazynu.');
}
}