<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;

class CategoryController extends Controller
{
    // public function index(Request $request)
    // {
    //     $categories = Category::all();
    //     $products = Product::all();
    //     return view('categories', compact('categories', 'products'));
    // }
    
    public function show(string $name)
    {
        $category = Category::where('name', $name)->first();
        if (!$category) {
            abort(404);
        }
        $products = Product::where('category_id', $category->id)->get();
        return view('categories.show', compact('category', 'products'));
    }

    public function genderIndex($name)
    {
        // Szukamy kategorii "Kobieta" lub "Mężczyzna" po nazwie z URL
        $mainCategory = Category::where('name', $name)->firstOrFail();

        // Pobieramy Poziom 2 (np. Odzież damska) oraz ich dzieci (Poziom 3: Bluzy) z produktami
        $sections = Category::where('parent_id', $mainCategory->id)
            ->with(['children.products' => function($query) {
                $query->take(4); // Limit produktów dla każdej najniższej kategorii
            }])
            ->get();

        // Zmieniamy ścieżkę widoku na categories.gender (zgodnie ze screenem)
        return view('categories.gender', compact('mainCategory', 'sections'));
    }

    public function index()
    {
        // Pobieramy tylko główne kategorie (te bez rodzica) z ich podkategoriami
        $categories = Category::whereNull('parent_id')
            ->with('children.children')
            ->get();

        return view('categories.index', compact('categories'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|unique:categories,slug',
            'parent_id' => 'nullable|exists:categories,id'
        ]);

        Category::create($data);

        return back()->with('success', 'Kategoria została dodana!');
    }


    public function destroy(Category $category)
    {
        // Opcjonalnie: sprawdź czy kategoria ma podkategorie lub produkty
        if ($category->children()->count() > 0) {
            return back()->with('error', 'Nie można usunąć kategorii, która posiada podkategorie!');
        }

        $category->delete();

        return back()->with('success', 'Kategoria została usunięta.');
    }

    public function update(Request $request, Category $category)
    {
        $data = $request->validate([
            'name'      => 'required|string|max:255',
            'slug'      => 'required|string|unique:categories,slug,' . $category->id,
            'parent_id' => 'nullable|exists:categories,id'
        ]);

        $category->update($data);

        return back()->with('success', 'Kategoria została zaktualizowana!');
    }
}
