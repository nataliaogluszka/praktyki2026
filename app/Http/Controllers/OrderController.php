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
    public function index(Request $request)
    {
        $query = Order::query();
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->sort === 'oldest') {
            $query->orderBy('created_at', 'asc');
        } else {
            $query->orderBy('created_at', 'desc');
        }

        $orders = $query->paginate(10);

        return view('orders.index', compact('orders'));
    }

    public function update(Request $request, Order $order)
    {
        $request->validate([
            'status' => 'required|in:Opłacone,Wysłane',
        ]);
        $order->update(['status' => $request->status]);
        return back()->with('success', 'Status zaktualizowany!');
    }

    public function show(Order $order)
    {
        $order->load('orderItems');
        return view('orders.show', compact('order'));
    }

    public function noteUpdate(Request $request, Order $order)
    {
        $request->validate([
            'internal_notes' => 'nullable|string|max:5000',
        ]);

        $order->update([
            'internal_notes' => $request->internal_notes
        ]);

        return back()->with('success', 'Notatka została zaktualizowana.');
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
    

    public function destroy(Order $order)
    {
        $order->delete();

        return back()->with('success', 'Zamówienie zostało anulowane.');
    }

    public function destroyAdmin($id)
    {
        $order = Order::findOrFail($id);

        $order->delete();

        return redirect()->route('orders.index')
            ->with('success', 'Zamówienie zostało pomyślnie usunięte.');
    }

    public function confirmDelivery(Order $order)
    {
        // Sprawdź, czy zamówienie należy do zalogowanego użytkownika
        if ($order->user_id !== auth::id()) {
            abort(403);
        }

        // Zmień status na 'dostarczono' (pisownia małą literą, zgodnie z Twoim widokiem Blade)
        $order->update([
            'status' => 'dostarczono'
        ]);

        return back()->with('success', 'Potwierdzono odbiór zamówienia. Teraz możesz dokonać zwrotu, jeśli jest taka potrzeba.');
    }
}