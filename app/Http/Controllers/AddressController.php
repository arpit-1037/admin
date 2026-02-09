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
        $validated = $request->validate(
            [
                'name'         => 'required|string|max:100',
                'phone'        => 'required|digits_between:10,15',
                'address_line' => 'required|string|max:255',
                'city'         => 'required|string|max:100',
                'state'        => 'required|string|max:100',
                'postal_code'  => 'required|string|max:20',
            ],
            [
                'name.required'         => 'Name is required.',
                'phone.required'        => 'Phone number is required.',
                'phone.digits_between'  => 'Phone number must be between 10 and 15 digits.',
                'address_line.required' => 'Address line is required.',
                'city.required'         => 'City is required.',
                'state.required'        => 'State is required.',
                'postal_code.required'  => 'Postal code is required.',
            ]
        );

        Address::create([
            'user_id'      => auth::id(),
            'name'         => $validated['name'],
            'phone'        => $validated['phone'],
            'address_line' => $validated['address_line'],
            'city'         => $validated['city'],
            'state'        => $validated['state'],
            'postal_code'  => $validated['postal_code'],
        ]);

        return redirect()->back()->with('success', 'Address added successfully.');
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
