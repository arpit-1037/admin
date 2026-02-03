<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Your Cart</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100">

    <div class="max-w-5xl mx-auto px-4 py-10">

        <div class="flex items-center justify-between mb-6">
            <h1 class="text-2xl font-bold text-gray-800">
                Your Cart
            </h1>

            <button id="clear-cart" class="bg-red-600 text-white px-4 py-2 rounded-full hover:bg-red-700">
                Clear Cart
            </button>
        </div>

        @if ($cartItems->isEmpty())
            <div id="empty-cart" class="bg-white p-6 rounded shadow text-center text-gray-600">
                Your cart is empty.
            </div>
        @else

            <div id="cart-wrapper" class="bg-white rounded shadow overflow-hidden">

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
        @endif

        <div class="mt-6">
            <a href="{{ route('guest.products.index') }}" class="text-sm text-indigo-600 hover:underline">
                ← Continue Shopping
            </a>
        </div>

        <div class="mt-6">
            <form action="{{ route('order.summary') }}" method="get">
                @csrf
                <button type="submit" class="w-full bg-indigo-600 text-white py-3 rounded-lg font-semibold">
                    Place Order
                </button>
            </form>
        </div>

    </div>

    {{-- AJAX CART SCRIPT --}}
    <script>
        const CART_ACTION_URL = "{{ route('cart.action') }}";
        const CSRF_TOKEN = "{{ csrf_token() }}";

        // REMOVE ITEM
        document.addEventListener('click', function (e) {
            const btn = e.target.closest('.remove-item');
            if (!btn) return;

            const productId = btn.dataset.productId;
            const row = btn.closest('.cart-row');

            fetch(CART_ACTION_URL, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': CSRF_TOKEN
                },
                body: JSON.stringify({
                    action: 'remove',
                    product_id: productId
                })
            })
                .then(res => res.json())
                .then(data => {
                    if (!data.success) {
                        alert(data.message);
                        return;
                    }

                    row.remove();
                    recalculateTotal();

                    if (document.querySelectorAll('.cart-row').length === 0) {
                        document.getElementById('cart-wrapper').remove();
                        document.getElementById('empty-cart')?.classList.remove('hidden');
                    }
                });
        });

        // CLEAR CART
        document.getElementById('clear-cart')?.addEventListener('click', function () {
            if (!confirm('Clear entire cart?')) return;

            fetch(CART_ACTION_URL, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': CSRF_TOKEN
                },
                body: JSON.stringify({
                    action: 'clear_all'
                })
            })
                .then(res => res.json())
                .then(data => {
                    if (!data.success) {
                        alert(data.message);
                        return;
                    }

                    document.getElementById('cart-wrapper')?.remove();
                    document.getElementById('empty-cart')?.classList.remove('hidden');
                    updateCartBadge(0);
                });
        });

        function recalculateTotal() {
            let total = 0;

            document.querySelectorAll('.cart-row').forEach(row => {
                const price = parseFloat(row.dataset.price);
                const qty = parseInt(row.dataset.quantity);
                total += price * qty;
            });

            document.getElementById('cart-total').textContent =
                'Total: ₹' + total.toFixed(2);

            updateCartBadge(document.querySelectorAll('.cart-row').length);
        }

        function updateCartBadge(count) {
            const badge = document.getElementById('cart-count');
            if (!badge) return;

            badge.textContent = count;
            badge.classList.toggle('hidden', count === 0);
        }
    </script>

</body>

</html>