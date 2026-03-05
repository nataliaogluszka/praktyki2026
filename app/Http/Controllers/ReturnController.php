<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderReturn;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class ReturnController extends Controller
{

    public function index()
    {
        $returns = OrderReturn::where('user_id', Auth::id())
            ->with('order')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('returns.user.index', compact('returns'));
    }


    public function store(Request $request)
    {
        $request->validate([
            'order_id' => 'required|exists:orders,id',
            'reason' => 'required|string|max:500',
        ]);

        $order = Order::findOrFail($request->order_id);

        if ($order->user_id !== Auth::id() || !$this->isEligibleForReturn($order)) {
            return redirect()->route('orders.index')->with('error', 'Akcja zabroniona.');
        }

        OrderReturn::create([
            'order_id' => $order->id,
            'user_id' => Auth::id(),
            'reason' => $request->reason,
            'status' => 'Oczekuje',
        ]);

        return redirect()->route('returns.index')
            ->with('success', 'Zgłoszenie zwrotu zostało wysłane pomyślnie.');
    }

    /**
     * Logika biznesowa sprawdzająca możliwość zwrotu.
     */
    private function isEligibleForReturn(Order $order)
    {
        $isDelivered = strtolower(trim($order->status)) === 'dostarczono';
        $isWithinTimeLimit = $order->created_at->diffInDays(now()) <= 14;

        return $isDelivered && $isWithinTimeLimit;
    }

    public function indexAdmin()
    {
        $returns = OrderReturn::with(['order', 'user'])
            ->latest()
            ->paginate(20);

        return view('returns.index', compact('returns'));
    }

    public function updateStatus(Request $request, OrderReturn $return)
    {
        $request->validate([
            'status' => 'required|in:Oczekuje,Zatwierdzony,Odrzucony,Zakończony'
        ]);

        $return->update([
            'status' => $request->status
        ]);

        return back()->with('success', 'Status zwrotu został zaktualizowany.');
    }
}