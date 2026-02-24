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
    // Metoda wyświetlająca listę
    public function index(Request $request)
    {
        $query = Order::query();

        // Filtrowanie po statusie
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Sortowanie
        if ($request->sort === 'oldest') {
            $query->orderBy('created_at', 'asc');
        } else {
            $query->orderBy('created_at', 'desc');
        }

        $orders = $query->paginate(10);

        return view('orders.index', compact('orders'));
    }

    // Metoda aktualizująca status
    public function update(Request $request, Order $order)
    {
        
        $request->validate([
            'status' => 'required|in:w przygotowaniu,wysłano',
        ]);

        $order->update(['status' => $request->status]);
        
        return back()->with('success', 'Status zaktualizowany!');
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
    public function userIndex()
    {
         /** @var \App\Models\User $user */
        $user = Auth::user();
        $orders = Order::where('user_id', Auth::id())->paginate(5);

        return view('orders.user.index', [
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