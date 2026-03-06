<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use App\Models\Product;
use App\Models\Category;
use App\Models\Setting;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ProductController extends Controller
{

    public function index(Request $request)
    {
        $query = Product::query()->with(['category.parent', 'product_images', 'attributes']);

        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }

        switch ($request->sort) {
            case 'price_asc':
                $query->orderBy('price', 'asc');
                break;
            case 'price_desc':
                $query->orderBy('price', 'desc');
                break;
            case 'name_asc':
                $query->orderBy('name', 'asc');
                break;
            case 'newest':
            default:
                $query->orderBy('created_at', 'desc');
                break;
        }

        $products = $query->paginate(10)->withQueryString(); 
        $categories = Category::whereNull('parent_id')->with('children.children')->get();

        $existingAttributeNames = DB::table('product_attributes')->distinct()->pluck('name');

        return view('products.index', compact('products', 'categories', 'existingAttributeNames'));
    }

    public function update(Request $request, Product $product): RedirectResponse
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'description' => 'nullable|string',
            'category_id' => 'required|exists:categories,id',
            'image' => 'nullable|file|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'delete_images' => 'nullable|array',
            'attr_names' => 'nullable|array',
            'attr_values' => 'nullable|array',
        ]);

        DB::beginTransaction();
        try {
            $product->update($request->only([
                'name', 'price', 'description', 'category_id'
            ]));

            // Obsługa atrybutów
            $this->saveAttributes($product, $request);

            // Obsługa usuwania zdjęć
            if ($request->filled('delete_images')) {
                $imagesToDelete = $product->product_images()
                    ->whereIn('id', $request->delete_images)
                    ->get();

                foreach ($imagesToDelete as $image) {
                    $path = public_path('images/products/' . $image->path);
                    if (file_exists($path)) {
                        unlink($path);
                    }
                    $image->delete();
                }
            }

            if ($request->hasFile('image')) {
                $file = $request->file('image');
                $imageName = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
                $file->move(public_path('images/products'), $imageName);
                $product->product_images()->create(['path' => $imageName]);
            }

            DB::commit();
            return back()->with('success', 'Produkt zaktualizowany!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Błąd aktualizacji: ' . $e->getMessage());
        }
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'description' => 'nullable|string',
            'category_id' => 'required|exists:categories,id',
            'image' => 'nullable|file|image|mimes:jpeg,png,jpg,gif,webp|max:2048', 
            'attr_names' => 'nullable|array',
            'attr_values' => 'nullable|array',
        ]);

        DB::beginTransaction();
        try {
            $product = Product::create([
                'name' => $request->name,
                'price' => $request->price,
                'description' => $request->description,
                'category_id' => $request->category_id,
            ]);

            // Obsługa atrybutów
            $this->saveAttributes($product, $request);

            if ($request->hasFile('image')) {
                $file = $request->file('image');
                $imageName = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
                $file->move(public_path('images/products'), $imageName);
                $product->product_images()->create(['path' => $imageName]);
            }

            DB::commit();
            return back()->with('success', 'Produkt dodany!');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Błąd zapisu: ' . $e->getMessage());
        }
    }
    
    public function show(string $id)
    {
        $product = Product::with('product_images')->findOrFail($id);
        $opinions = $product->opinions()->with('user')->latest()->paginate(10);
        $vatRateSetting = Setting::where('key', 'vat_rate')->first();
        $vatRate = $vatRateSetting ? (float)$vatRateSetting->value : 23.0;

        return view('products.show', [
            'product' => $product,
            'vatRate' => $vatRate,
            'opinions' => $opinions,
        ]);
    }

    public function destroy(Product $product)
    {
        foreach ($product->product_images as $image) {
            $path = public_path('images/products/' . $image->path);
            if (file_exists($path)) {
                unlink($path);
            }
        }
        
        $product->delete();
        return back()->with('success', 'Produkt został usunięty.');
    }

    private function saveAttributes($product, $request) {
        $product->attributes()->delete();

        if ($request->has('attr_names')) {
            foreach ($request->attr_names as $index => $name) {
                if (!empty($name) && !empty($request->attr_values[$index])) {
                    $product->attributes()->create([
                        'name' => $name,
                        'value' => $request->attr_values[$index]
                    ]);
                }
            }
        }
    }
}