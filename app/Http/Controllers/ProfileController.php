<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Address;
use App\Http\Controllers\User;

class ProfileController extends Controller
{
    // Wyświetlanie profilu
    public function index()
    {
          /** @var \App\Models\User $user */
        $user = Auth::user();
        $addresses = $user->addresses;
        $orders = $user->orders()->latest()->take(5)->get();

        return view('profile', compact('user', 'addresses', 'orders'));
    }

    public function update(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'surname' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
        ]);

          /** @var \App\Models\User $user */
        $user->update($validated);

        return back()->with('success', 'Dane zostały zaktualizowane.');
    }

    // Dodawanie adresu
    public function storeAddress(Request $request)
    {
        $validated = $request->validate([
            'street' => 'required|string|max:255',
            'house_number' => 'required|string|max:20',
            'postal_code' => 'required|string|max:10',
            'city' => 'required|string|max:255',
            'country' => 'required|string',
        ]);

        $user = Auth::user();
          /** @var \App\Models\User $user */
        $user->addresses()->create($validated);

        return back()->with('success', 'Nowy adres został dodany.');
    }

    public function destroyAddress(Address $address)
    {
        if ($address->user_id !== Auth::id()) {
            abort(403);
        }
        $address->delete();
        return back()->with('success', 'Adres został usunięty.');
    }

    public function updateMarketing(Request $request)
    {
        /** @var User $user */

        $user = Auth::user();
        
        $user->marketingConsents()->updateOrCreate(
            ['user_id' => $user->id],
            [
                'newsletter' => $request->has('newsletter'),
                'sms_marketing' => $request->has('sms_marketing'),
                'data_processing_third_party' => $request->has('data_processing_third_party'),
            ]
        );

        return back()->with('success', 'Zgody marketingowe zostały zaktualizowane.');
    }
}