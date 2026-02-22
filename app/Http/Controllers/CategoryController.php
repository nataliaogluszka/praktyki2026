<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;

class CategoryController extends Controller
{
    public function index(Request $request)
    {
        $categories = Category::all();
        $products = Product::all();
        return view('categories', compact('categories', 'products'));
    }
    
    public function show(string $name)
    {
        $category = Category::where('name', $name)->first();
        if (!$category) {
            abort(404);
        }
        $products = Product::where('category_id', $category->id)->get();
        return view('categories.show', compact('category', 'products'));
    }
}
