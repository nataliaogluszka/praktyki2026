<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Support\Facades\Storage;
use App\Models\Inventory;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        return view('products.index', [          
            'products' => Product::with('category')->paginate(8), // Dodałem eager loading kategorii
            // Pobieramy tylko główne kategorie z ich dziećmi
            'categories' => Category::whereNull('parent_id')->with('children.children')->get()
        ]);
    }
    
    public function show(string $id)
    {
        $product = Product::findOrFail($id);

        return view('products.show', [
            'product' => $product
        ]);
    }

    public function destroy(Product $product)
    {

        $product->delete();

        return back()->with('success', 'Produkt został usunięty.');
    }



    public function edit(Product $product)
    {
        // Pobieramy drzewo kategorii (3 poziomy)
        $categories = Category::whereNull('parent_id')->with('children.children')->get();
        return view('products.edit', compact('product', 'categories'));
    }

    public function update(Request $request, Product $product): RedirectResponse
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'description' => 'nullable|string',
            'category_id' => 'required|exists:categories,id',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ]);

        if ($request->hasFile('image')) {
            // 1. Usuwamy stare zdjęcie, jeśli nie jest domyślne
            if ($product->image && $product->image !== 'default.jpg') {
                $oldPath = public_path('images/products/' . $product->image);
                if (file_exists($oldPath)) {
                    unlink($oldPath);
                }
            }

            // 2. Obsługa nowego zdjęcia (analogicznie do store)
            $image = $request->file('image');
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('images/products'), $imageName);
            
            $data['image'] = $imageName;
        } else {
            // Jeśli nie przesłano nowego zdjęcia, usuwamy 'image' z tablicy $data,
            // aby Laravel nie próbował go nadpisać w bazie (zostanie stare).
            unset($data['image']);
        }

        $product->update($data);

        return redirect()->route('products.index')
            ->with('success', 'Produkt został zaktualizowany!');
    }

    public function store(Request $request) {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'description' => 'nullable|string',
            'category_id' => 'required|exists:categories,id',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ]);

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            // Tworzymy unikalną nazwę pliku
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            // Przenosimy plik do folderu public/images/products
            $image->move(public_path('images/products'), $imageName);
            // Zapisujemy ścieżkę do tablicy danych
            $data['image'] = $imageName;
        } else {
            $data['image'] = 'default.jpg'; // Domyślny obrazek
        }

        $product = Product::create($data); 

        Inventory::create([
            'product_id' => $product->id,
            'quantity' => 0,
        ]);

        return back()->with('success', 'Produkt został dodany pomyślnie!');
    }
}
