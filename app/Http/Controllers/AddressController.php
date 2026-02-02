<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Address;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

// app/Http/Controllers/AddressController.php
class AddressController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'phone' => 'required',
            'address_line' => 'required',
            'city' => 'required',
            'state' => 'required',
            'postal_code' => 'required',
        ]);

        Address::create([
            'user_id' => auth::id(),
            'name' => $request->name,
            'phone' => $request->phone,
            'address_line' => $request->address_line,
            'city' => $request->city,
            'state' => $request->state,
            'postal_code' => $request->postal_code,
        ]);

        return back()->with('success', 'Address added successfully');
    }
    public function select(Request $request)
    {
        $request->validate([
            'address_id' => [
                'required',
                Rule::exists('addresses', 'id')
                    ->where('user_id', Auth::id()),
            ],
        ]);
        /**
         * Persist selected address for checkout
         * (session is correct for non-AJAX flow)
         */
        session(['checkout.address_id' => $request->address_id]);
        return redirect()->route('checkout.index');
    }
}
