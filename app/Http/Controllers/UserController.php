<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('users.index', [          
            'users' => User::paginate(8)
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
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function updateRole(Request $request, User $user)
    {
        $request->validate([
            'role' => 'required|in:user,admin',
        ]);

        $user->update(['role' => $request->role]);

        return back()->with('success', 'Rola została zaktualizowana.');
    }

    // Usuwanie użytkownika
    public function destroy(User $user)
    {
        // Opcjonalnie: zabezpieczenie, aby admin nie usunął samego siebie
        if (Auth::id() === $user->id) {
            return back()->with('error', 'Nie możesz usunąć własnego konta!');
        }

        $user->delete();

        return back()->with('success', 'Użytkownik został usunięty.');
    }
}
