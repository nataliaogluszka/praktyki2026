<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;
use App\Models\Product;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        // $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    // public function index()
    // {
    //     // return view('home');
    //     return view('home', [          
    //         'products' => Product::all()
    //     ]);
    // }


    public function index(Request $request): View
    {
        $sort = $request->get('sort', 'newest');

        $products = Product::query()
            ->when($sort == 'price_asc', function ($q) {
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
            })
            ->paginate(6)
            ->withQueryString();

        return view('home', [
            'products' => $products,
            'currentSort' => $sort
        ]);
    }
}
