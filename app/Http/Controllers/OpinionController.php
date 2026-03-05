<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Opinion;
use Illuminate\Support\Facades\Auth;

class OpinionController extends Controller
{
    public function index(Request $request)
    {
        //
    }

    public function create()
    {
        //
    }

    public function store(Request $request, $productId)
    {
        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'required|string|max:1000',
        ]);

        Opinion::create([
            'product_id' => $productId,
            'user_id' => Auth::id(),
            'rating' => $request->rating,
            'comment' => $request->comment,
        ]);

        return back()->with('success', 'Twoja opinia została dodana!');
    }


    public function destroy(Opinion $opinion)
    {
        if(Auth::id() !== $opinion->user_id){
            abort(403, 'Nie masz uprawnień do usunięcia tej opinii.');
        }

        $opinion->delete();
        return back()->with('success', 'Opinia została usunięta');
    }
}