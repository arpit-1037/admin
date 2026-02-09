    @extends('layouts.user')

@section('title', 'Order Summary')

@section('content')
    <body class="bg-gray-100">
<div class="max-w-4xl mx-auto p-6 bg-white rounded-xl shadow mt-10">

    <h2 class="text-xl font-bold mb-4">Delivery Address</h2>

    {{-- ✅ MAIN CHECKOUT FORM (RADIOS + BUTTON TOGETHER) --}}
    <form action="{{ route('address.select') }}" method="POST">
        @csrf

        {{-- Address List --}}
        <div class="space-y-3">
            @forelse($addresses as $address)
                <label class="block border p-3 rounded-lg cursor-pointer">
                    <input
                        type="radio"
                        name="address_id"
                        value="{{ $address->id }}"
                        {{ old('address_id') == $address->id ? 'checked' : '' }}
                    >

                    <div class="text-sm text-gray-700 mt-1">
                        {{ $address->address_line }},
                        {{ $address->city }},
                        {{ $address->state }} - {{ $address->postal_code }}
                    </div>

                    <div class="text-sm text-gray-500">
                        {{ $address->phone }}
                    </div>
                </label>
            @empty
                <p class="text-sm text-gray-500">
                    No saved addresses found.
                </p>
            @endforelse
        </div>

        {{-- Validation Error --}}
        @error('address_id')
            <p class="text-red-600 text-sm mt-2">
                {{ $message }}
            </p>
        @enderror

        {{-- Order Summary --}}
        <h2 class="text-xl font-bold mt-6 mb-4">Order Summary</h2>

        @foreach($cartItems as $item)
            <div class="flex justify-between text-sm mb-2">
                <span>{{ $item->product->name }} × {{ $item->quantity }}</span>
                <span>₹{{ number_format($item->product->price * $item->quantity, 2) }}</span>
            </div>
        @endforeach

        <div class="flex justify-between font-bold border-t pt-4 mt-4">
            <span>Total</span>
            <span>₹{{ number_format($total, 2) }}</span>
        </div>

        {{-- Checkout Button --}}
        <button
            type="submit"
            class="w-full mt-6 bg-indigo-600 text-white py-3 rounded-lg font-semibold">
            Checkout
        </button>
    </form>

    {{-- Add Address Toggle --}}
    <div class="mt-8">
        <button
            type="button"
            onclick="toggleAddressForm()"
            class="text-indigo-600 font-semibold hover:underline">
            + Add New Address
        </button>
    </div>

    @if ($errors->any())
    <div class="mb-4 rounded-lg bg-red-100 p-4 text-red-700">
        <strong class="block mb-2">Please fix the following errors:</strong>
        <ul class="list-disc list-inside">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

@if (session('success'))
    <div class="mb-4 rounded-lg bg-green-100 p-4 text-green-700">
        {{ session('success') }}
    </div>
@endif

    {{-- ADD ADDRESS FORM (SEPARATE FORM — CORRECT) --}}
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
@endsection