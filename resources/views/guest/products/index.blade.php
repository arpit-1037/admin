<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Products</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    @vite(['resources/css/app.css'])
</head>

<body class="bg-blue-100">

    <header class="bg-white shadow">
        <div class="max-w-7xl mx-auto px-4 py-4 flex justify-between">
            <h1 class="text-xl font-semibold text-gray-800">
                <a href="/">Product Catalog</a>
            </h1>

            <div class="space-x-4">
                @auth
                    <a href="{{ route('cart.view') }}"
                        class="relative inline-flex items-center bg-blue-600 hover:bg-indigo-500 text-white px-4 py-1.5 rounded-md text-sm font-medium shadow transition">
                        <span>Cart</span>

                        <span id="cart-count"
                            class="absolute -top-2 -right-2 bg-red-600 text-white text-xs font-bold rounded-full px-2 py-0.5 {{ $cartCount > 0 ? '' : 'hidden' }}">
                            {{ $cartCount }}
                        </span>
                    </a>

                    <form method="POST" action="{{ route('logout') }}" class="inline">
                        @csrf
                        <button type="submit"
                            class="text-sm text-white rounded-md bg-red-600 hover:bg-red-700 transition px-3 py-1.5">
                            Logout
                        </button>
                    </form>
                @endauth

                @guest
                    <a href="{{ route('login') }}" class="text-sm text-blue-600 hover:underline">Login</a>
                    <a href="{{ route('register') }}" class="text-sm text-blue-600 hover:underline">Register</a>
                @endguest
            </div>
        </div>
    </header>

    <div class="max-w-7xl mx-auto px-4 py-10">
        <div class="grid grid-cols-4 gap-6">

            @foreach ($products as $product)
                @php
                    $imageUrl = $product->primaryImage
                        ? asset('storage/' . $product->primaryImage->path)
                        : asset('storage/placeholders/product.svg');
                @endphp

                <div class="bg-white border rounded-2xl shadow-sm hover:shadow-lg transition flex flex-col overflow-hidden">

                    {{-- Image --}}
                    <div class="aspect-[4/3] bg-gray-100 overflow-hidden">
                        <img src="{{ $imageUrl }}" class="h-full w-full object-cover"
                            onerror="this.src='{{ asset('storage/placeholders/product.svg') }}'">
                    </div>

                    {{-- Content --}}
                    <div class="p-4 flex flex-col flex-grow">

                        <div class="mb-3">
                            <h3 class="text-lg font-bold text-gray-900">{{ $product->name }}</h3>
                            <p class="text-indigo-600 font-bold">₹{{ number_format($product->price, 2) }}</p>
                            <p class="text-sm text-gray-500 mt-1 line-clamp-2">
                                {{ $product->description }}
                            </p>
                        </div>

                        {{-- Footer --}}
                        <div class="mt-auto pt-3 border-t flex justify-between items-center">

                            {{-- Quantity Counter --}}
                            <div class="inline-flex items-center border rounded-full bg-gray-50" data-min="1" data-max="10">
                                <button type="button"
                                    class="qty-decrease h-7 w-7 flex justify-center items-center text-gray-600">
                                    −
                                </button>

                                <input type="text"
                                    class="qty-input w-8 text-center bg-transparent text-gray-800 font-semibold text-xs"
                                    value="1" readonly>

                                <button type="button"
                                    class="qty-increase h-7 w-7 flex justify-center items-center text-gray-600">
                                    +
                                </button>
                            </div>

                            {{-- Add to Cart --}}
                            @auth
                                <div class="flex items-center">
                                    <input type="hidden" class="qty-hidden" value="1">

                                    <button type="button"
                                        class="add-to-cart inline-flex items-center gap-1 bg-indigo-600 hover:bg-indigo-700 text-white px-3 py-1.5 rounded-full text-xs font-semibold"
                                        data-product-id="{{ $product->id }}">
                                        Add to Cart
                                    </button>
                                </div>
                            @endauth

                            @guest
                                <a href="{{ route('login') }}"
                                    class="bg-gray-600 hover:bg-gray-500 text-white px-3 py-1.5 rounded-full text-xs font-semibold">
                                    Login to add
                                </a>
                            @endguest

                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="mt-10">
            {{ $products->links() }}
        </div>
    </div>

    {{-- Quantity + AJAX Cart Script --}}
    <script>
        // Quantity controls
        document.addEventListener('click', function (e) {
            const wrapper = e.target.closest('[data-min]');
            if (!wrapper) return;

            const input = wrapper.querySelector('.qty-input');
            const hidden = wrapper.closest('.flex')?.querySelector('.qty-hidden');
            const min = parseInt(wrapper.dataset.min);
            const max = parseInt(wrapper.dataset.max);
            let value = parseInt(input.value);

            if (e.target.classList.contains('qty-increase') && value < max) value++;
            if (e.target.classList.contains('qty-decrease') && value > min) value--;

            input.value = value;
            if (hidden) hidden.value = value;
        });

        // Add to cart AJAX
        document.addEventListener('click', function (e) {
            const btn = e.target.closest('.add-to-cart');
            if (!btn) return;

            const productId = btn.dataset.productId;
            const qtyInput = btn.closest('.flex')?.querySelector('.qty-hidden');
            const quantity = qtyInput ? qtyInput.value : 1;

            btn.disabled = true;

            fetch("{{ route('cart.action') }}", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    action: 'add',
                    product_id: productId,
                    quantity: quantity
                })
            })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        updateCartCount(data.cart_count);
                    } else {
                        alert(data.message);
                    }
                })
                .catch(() => alert('Something went wrong'))
                .finally(() => btn.disabled = false);
        });

        function updateCartCount(count) {
            const el = document.getElementById('cart-count');
            if (!el) return;

            el.textContent = count;
            el.classList.remove('hidden');
        }
    </script>

</body>

</html>