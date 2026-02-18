<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        //
    }
    
    public function show(string $id)
    {
        $product = Product::findOrFail($id);


    return view('products.show', [
        'product' => $product
    ]);
    }
}
