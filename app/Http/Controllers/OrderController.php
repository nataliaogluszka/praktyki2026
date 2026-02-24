<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('orders.index', [          
            'orders' => Order::paginate(8)
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show()
    {
         /** @var \App\Models\User $user */
        $user = Auth::user();
        $orders = $user->orders()->get();

        return view('orders.show', [
            'orders' => $orders
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    

    // Usuwanie użytkownika
    public function destroy(Order $order)
    {
        $order->delete();

        return back()->with('success', 'Zamówienie zostało anulowane.');
    }
}
