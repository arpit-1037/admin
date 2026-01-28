<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Order Summary</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100">

<p class="text-red-600 font-bold">
    {{ auth()->check() ? 'LOGGED IN' : 'NOT LOGGED IN' }}
</p>

<div class="max-w-4xl mx-auto p-6 bg-white rounded-xl shadow mt-10">

    <h2 class="text-xl font-bold mb-4">Delivery Address</h2>

    {{-- SINGLE, VALID FORM --}}
    

        {{-- Address List --}}
        <div class="space-y-3">
            @if(!empty($addresses) && count($addresses))
                @foreach($addresses as $address)
                    <label class="block border p-3 rounded-lg cursor-pointer">
                        <input type="radio"
                               name="address_id"
                               value="{{ $address->id }}"
                               required>
                        <div class="text-sm text-gray-700 mt-1">
                            {{ $address->address_line }},
                            {{ $address->city }},
                            {{ $address->state }} - {{ $address->postal_code }}
                        </div>
                        <div class="text-sm text-gray-500">
                            {{ $address->phone }}
                        </div>
                    </label>
                @endforeach
            @else
                <p class="text-sm text-gray-500">
                    No saved addresses found.
                </p>
            @endif
        </div>

        {{-- Add Address Toggle --}}
        <div class="mt-8">
            <button type="button"
                onclick="toggleAddressForm()"
                class="text-indigo-600 font-semibold hover:underline">
                + Add New Address
            </button>
        </div>

        {{-- Order Summary --}}
        <h2 class="text-xl font-bold mt-6 mb-4">Order Summary</h2>

        @if(!empty($cartItems))
            @foreach($cartItems as $item)
                <div class="flex justify-between text-sm mb-2">
                    <span>{{ $item->product->name }} × {{ $item->quantity }}</span>
                    <span>
                        ${{ number_format($item->product->price * $item->quantity, 2) }}
                    </span>
                </div>
            @endforeach
        @endif

        <div class="flex justify-between font-bold border-t pt-4 mt-4">
            <span>Total</span>
            <span>${{ number_format($total, 2) }}</span>
        </div>
    <form action="{{ route('checkout.index') }}" method="get">
        @csrf

        {{-- Checkout Button --}}
        <button type="submit"
            class="w-full mt-6 bg-indigo-600 text-white py-3 rounded-lg font-semibold">
            Checkout
        </button>
    </form>

    {{-- ADD ADDRESS FORM (SEPARATE, NOT NESTED) --}}
    <div id="addressForm" class="hidden mt-6 border-t pt-6">

        <h2 class="text-xl font-bold mb-4">Add New Address</h2>

        <form action="{{ route('address.store') }}" method="POST" class="space-y-3">
            @csrf

            <input type="text" name="name" placeholder="Full Name"
                   class="w-full border p-2 rounded" required>

            <input type="text" name="phone" placeholder="Phone Number"
                   class="w-full border p-2 rounded" required>

            <textarea name="address_line" placeholder="Street Address"
                      class="w-full border p-2 rounded" required></textarea>

            <div class="grid grid-cols-3 gap-2">
                <input type="text" name="city" placeholder="City"
                       class="border p-2 rounded" required>

                <input type="text" name="state" placeholder="State"
                       class="border p-2 rounded" required>

                <input type="text" name="postal_code" placeholder="ZIP Code"
                       class="border p-2 rounded" required>
            </div>

            <button type="submit"
                class="w-full bg-gray-800 text-white py-2 rounded font-semibold">
                Save Address
            </button>
        </form>

    </div>
</div>

<script>
    function toggleAddressForm() {
        document.getElementById('addressForm').classList.toggle('hidden');
    }
</script>

</body>
</html>
