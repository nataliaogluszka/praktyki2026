<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;
use App\Models\Product;
use App\Models\Category; // Dodaj ten import

class HomeController extends Controller
{
    public function index(Request $request): View
    {
        // 1. Pobranie parametrów z Request
        $sort = $request->get('sort', 'newest');
        $searchTerm = $request->query('search');
        $minPrice = $request->query('min_price');
        $maxPrice = $request->query('max_price');
        $categoryId = $request->query('category');
        $availableOnly = $request->has('available_only');

        // 2. Budowanie zapytania z relacją do stanu magazynowego (inventory)
        $query = Product::query()->with('inventory');

        // Filtracja po wyszukiwanym haśle
        $query->when($searchTerm, function ($query, $searchTerm) {
            $query->where(function ($q) use ($searchTerm) {
                $q->where('name', 'LIKE', "%{$searchTerm}%")
                  ->orWhere('description', 'LIKE', "%{$searchTerm}%");
            });
        });

        // FILTR: Cena od
        $query->when($minPrice, function ($q) use ($minPrice) {
            return $q->where('price', '>=', $minPrice);
        });

        // FILTR: Cena do
        $query->when($maxPrice, function ($q) use ($maxPrice) {
            return $q->where('price', '<=', $maxPrice);
        });

        // FILTR: Kategoria
        $query->when($categoryId, function ($q) use ($categoryId) {
            return $q->where('category_id', $categoryId);
        });

        // FILTR: Dostępność (korzysta z relacji inventory)
        $query->when($availableOnly, function ($q) {
            return $q->whereHas('inventory', function ($innerQuery) {
                $innerQuery->where('quantity', '>', 0);
            });
        });

        // 3. Sortowanie
        $query->when($sort == 'price_asc', function ($q) {
            return $q->orderBy('price', 'asc');
        })
        ->when($sort == 'price_desc', function ($q) {
            return $q->orderBy('price', 'desc');
        })
        ->when($sort == 'name_asc', function ($q) {
            return $q->orderBy('name', 'asc');
        })
        ->when($sort == 'newest', function ($q) {
            return $q->orderBy('created_at', 'desc');
        });

        // 4. Pobranie danych
        $products = $query->paginate(6)->withQueryString();
        
        // Pobieramy kategorie dla selecta w panelu bocznym
        // Wybieramy tylko te, które mają parent_id (czyli najniższe poziomy)
        $categories = Category::whereNull('parent_id')->with('children.children')->get();

        return view('home', [
            'products' => $products,
            'categories' => $categories, // Przekazujemy kategorie do widoku
            'currentSort' => $sort
        ]);
    }
}