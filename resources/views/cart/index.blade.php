@extends('layouts.user')

@section('title', 'User Dashboard')

@push('scripts')
    {{-- CART ACTION SCRIPT (LOGIC ONLY) --}}
    <script>
        const CART_ACTION_URL = "{{ route('cart.action') }}";
        const CSRF = document.querySelector('meta[name="csrf-token"]').content;

        function handleEmptyCartUI() {
            document.getElementById('cart-wrapper')?.classList.add('hidden');
            document.getElementById('empty-cart')?.classList.remove('hidden');
            document.getElementById('continue-shopping')?.classList.remove('hidden');

            document.getElementById('clear-cart')?.classList.add('hidden');

            const btn = document.getElementById('place-order-btn');
            if (!btn) return;

            btn.disabled = false;
            btn.type = 'button';
            btn.textContent = 'Back to Shopping';

            btn.className =
                'w-full py-3 rounded-lg font-semibold bg-green-600 hover:bg-green-700 text-white';

            btn.onclick = () => window.location.href = '/';
        }

        // REMOVE ITEM  
        document.addEventListener('click', function (e) {
            const btn = e.target.closest('.remove-item');
            if (!btn) return;

            const productId = btn.dataset.productId;
            const row = btn.closest('.cart-row');

            alertConfirm({
                title: 'Remove item?',
                text: 'This item will be removed from your cart',
                confirmText: 'Remove'
            }).then(result => {
                if (!result.isConfirmed) return;

                alertLoading('Removing item...');

                fetch(CART_ACTION_URL, {
                    method: 'POST',
                    credentials: 'same-origin',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                        'X-CSRF-TOKEN': CSRF
                    },
                    body: new URLSearchParams({
                        action: 'remove',
                        product_id: productId
                    })
                })
                    .then(res => res.json())
                    .then(data => {
                        Swal.close();

                        if (!data.success) {
                            alertError(data.message);
                            return;
                        }

                        row.remove();
                        alertSuccess('Item removed');

                        if (!document.querySelector('.cart-row')) {
                            handleEmptyCartUI();
                        }
                    });
            });
        });

        // CLEAR CART
        document.getElementById('clear-cart')?.addEventListener('click', function () {
            alertConfirm({
                title: 'Clear cart?',
                text: 'All items will be removed',
                confirmText: 'Clear'
            }).then(result => {
                if (!result.isConfirmed) return;

                alertLoading('Clearing cart...');

                fetch(CART_ACTION_URL, {
                    method: 'POST',
                    credentials: 'same-origin',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                        'X-CSRF-TOKEN': CSRF
                    },
                    body: new URLSearchParams({ action: 'clear_all' })
                })
                    .then(res => res.json())
                    .then(data => {
                        Swal.close();
                        // alert(res);

                        if (!data.success) {
                            alertError(data.message);

                            return;
                        }
                        // document.getElementById("cart-count").textContent = res.cart_count;    
                        handleEmptyCartUI();
                        alertSuccess('Cart cleared');
                    });
            });
        });

        document.getElementById('place-order-form')?.addEventListener('submit', function (e) {
            if (document.getElementById('place-order-btn')?.disabled) {
                e.preventDefault();
                alertError('Your cart is empty. Please add items before placing an order.');
            }
        });
    </script>
@endpush

@section('content')

    <body class="bg-gray-100">

        <div class="max-w-5xl mx-auto px-4 py-10">

            <div class="flex items-center justify-between mb-6">
                <h1 class="text-2xl font-bold text-gray-800">
                    Your Cart
                </h1>

                <button id="clear-cart" class="bg-red-600 text-white px-4 py-2 rounded-full hover:bg-red-700" {{ $cartItems->isEmpty() ? 'hidden' : '' }}>
                    Clear Cart
                </button>
            </div>

            <div id="empty-cart"
                class="bg-white p-6 rounded shadow text-center text-gray-600  {{ $cartItems->isEmpty() ? '' : 'hidden' }}">
                Your cart is empty.
            </div>

            <div id="cart-wrapper"
                class="bg-white rounded shadow overflow-hidden {{ $cartItems->isEmpty() ? 'hidden' : '' }}">

                <table class="w-full text-sm">
                    <thead class="bg-gray-50 border-b">
                        <tr class="text-left text-gray-600">
                            <th class="p-4">Product</th>
                            <th class="p-4 text-center">Price</th>
                            <th class="p-4 text-center">Quantity</th>
                            <th class="p-4 text-right">Subtotal</th>
                            <th class="p-4 text-center">Action</th>
                        </tr>
                    </thead>

                    <tbody id="cart-body">
                        @foreach ($cartItems as $item)
                            <tr class="border-b cart-row" data-product-id="{{ $item->product_id }}"
                                data-price="{{ $item->product->price }}" data-quantity="{{ $item->quantity }}">

                                <td class="p-4">
                                    {{ $item->product->name }}
                                </td>

                                <td class="p-4 text-center">
                                    ₹{{ number_format($item->product->price, 2) }}
                                </td>

                                <td class="p-4 text-center">
                                    {{ $item->quantity }}
                                </td>

                                <td class="p-4 text-right font-semibold row-subtotal">
                                    ₹{{ number_format($item->product->price * $item->quantity, 2) }}
                                </td>

                                <td class="p-4 text-center">
                                    <button class="remove-item text-red-600 hover:text-red-800 text-sm font-semibold"
                                        data-product-id="{{ $item->product_id }}">
                                        Remove
                                    </button>
                                </td>

                            </tr>
                        @endforeach
                    </tbody>
                </table>

                <div class="p-4 flex justify-end bg-gray-50">
                    <div id="cart-total" class="text-lg font-bold text-gray-800">
                        Total: ₹{{ number_format($total, 2) }}
                    </div>
                </div>

            </div>


            <div class="mt-6 shopping" id="continue-shopping">
                <a href="{{ route('guest.products.index') }}" class="text-sm text-indigo-600 hover:underline">
                    ← Continue Shopping
                </a>
            </div>

            <div class="mt-6">
                <form id="place-order-form"
                    action="{{ $cartItems->isEmpty() ? route('guest.products.index') : route('order.summary') }}"
                    method="get">
                    @csrf
                    <button id="place-order-btn" type="{{ $cartItems->isEmpty() ? 'button' : 'submit' }}" class="w-full py-3 rounded-lg font-semibold
                    {{ $cartItems->isEmpty()
        ? 'bg-green-600 hover:bg-green-700 text-white'
        : 'bg-indigo-600 hover:bg-indigo-700 text-white' }}">
                        {{ $cartItems->isEmpty() ? 'Back to Shopping' : 'Place Order' }}
                    </button>
                </form>
            </div>

        </div>
    </body>


@endsection