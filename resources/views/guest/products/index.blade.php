<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Products</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    @vite(['resources/css/app.css'])
</head>
<header class="bg-white shadow">
    <div class="max-w-7xl mx-auto px-4 py-4 flex justify-between">
        <h1 class="text-xl font-semibold text-gray-800">
            Product Catalog
        </h1>

        <div class="space-x-4">
            @guest
                <a href="{{ route('login') }}"
                    class="relative inline-flex items-center bg-gray-600 hover:bg-gray-500 text-white px-4 py-1.5 rounded-md text-sm font-medium shadow transition">
                    Cart
                </a>
                <a href="{{ route('login') }}" class="text-sm text-blue-600 hover:underline">
                    Login
                </a>
                <a href="{{ route('register') }}" class="text-sm text-blue-600 hover:underline">
                    Register
                </a>
            @endguest

            @auth
                {{-- <a href="{{ route('cart.view') }}" class="text-sm text-blue-600 hover:underline">
                    Cart
                </a> --}}
                
                    <a href="{{ route('cart.view') }}"
                        class="relative inline-flex items-center bg-blue-600 hover:bg-indigo-500 text-white px-4 py-1.5 rounded-md text-sm font-medium shadow transition">

                        <span>Cart</span>

                        @if ($cartCount > 0)
                            <span class="absolute -top-2 -right-2 bg-red-600 text-white text-xs font-bold rounded-full px-2 py-0.5">
                                {{ $cartCount }}
                            </span>
                        @endif
                    </a>
                <form method="POST" action="{{ route('logout') }}" class="inline">
                    @csrf
                    <button type="submit" class="text-sm text-red-600 hover:underline">
                        Logout
                    </button>
                </form>
            @endauth
        </div>
    </div>
</header>

<body class="bg-blue-100">

    <div class="max-w-7xl mx-auto px-4 py-10">

        <div class="grid grid-cols-4 gap-6">

            @foreach ($products as $product)
                @php
                    $name = $product->name ?? 'Unnamed Product';
                    $price = $product->price ?? 0;
                    $description = $product->description ?? '';
                    $category = $product->category->name ?? '';

                    if ($product->primaryImage) {
                        $imageUrl = asset('storage/' . $product->primaryImage->path);
                    } else {
                        $imageUrl = asset('storage/placeholders/product.svg');
                    }
                @endphp

                <div
                    class="product-card group relative bg-white border border-gray-200 rounded-2xl shadow-sm hover:shadow-lg transition-all duration-300 flex flex-col overflow-hidden h-full">

                    {{-- Image --}}
                    <div class="aspect-[4/3] bg-gray-100 overflow-hidden relative border-b border-gray-100">
                        <img src="{{ $imageUrl }}" alt="{{ $name }}"
                            class="h-full w-full object-cover object-center group-hover:scale-105 transition-transform duration-500 ease-out"
                            onerror="this.onerror=null;this.src='{{ asset('storage/placeholders/product.svg') }}';" />
                        @if ($category)
                            <span
                                class="absolute top-2 left-2 bg-white/90 backdrop-blur-sm text-xs font-semibold px-2.5 py-1 rounded-full text-gray-700 shadow-sm">
                                {{ $category }}
                            </span>
                        @endif
                    </div>

                    {{-- Content --}}
                    <div class="m-1 p-4 flex flex-col">

                        <div class="mb-2">
                            <div class="flex items-start justify-between">
                                <h3 class="text-lg font-bold text-gray-900 leading-tight">
                                    {{ $name }}
                                </h3>

                                <p class="text-lg font-bold text-indigo-600 ml-4 whitespace-nowrap">
                                    ₹{{ number_format($price, 2) }}
                                </p>
                            </div>

                            <p class="mt-1 text-sm text-gray-500 leading-relaxed line-clamp-2">
                                {{ $description }}
                            </p>
                        </div>

                        {{-- Footer --}}
                        <div
                            class="mt-auto pt-3 border-t border-gray-100 flex items-center justify-between text-xs text-gray-400">

                            {{-- Quantity Control (UI only) --}}
                            <div
                                class="inline-flex items-center rounded-full border border-gray-200 bg-gray-50 shadow-sm overflow-hidden">
                                <button type="button" class="h-7 w-7 flex items-center justify-center text-gray-600">
                                    −
                                </button>

                                <input type="text"
                                    class="w-8 text-center bg-transparent text-gray-800 font-semibold text-xs" value="1"
                                    readonly>

                                <button type="button" class="h-7 w-7 flex items-center justify-center text-gray-600">
                                    +
                                </button>
                            </div>

                            {{-- Add to Cart --}}
                            @auth
                                <form method="POST" action="{{ route('cart.add') }}">
                                    @csrf
                                    <input type="hidden" name="product_id" value="{{ $product->id }}">

                                    <button type="submit"
                                        class="inline-flex items-center gap-1 rounded-full bg-indigo-600 px-3 py-1.5 text-xs font-semibold text-white shadow-sm hover:bg-indigo-700 transition-all">
                                        <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M3 3h2l.4 2M7 13h10l3-8H6.4M7 13L5.4 5M7 13l-2 6h14" />
                                        </svg>
                                        <span>Add to Cart</span>
                                    </button>
                                </form>
                            @endauth

                            @guest
                                <a href="{{ route('login') }}"
                                    class="inline-flex items-center gap-1 rounded-full bg-gray-600 px-3 py-1.5 text-xs font-semibold text-white shadow-sm hover:bg-gray-500 transition-all">
                                    Login to add
                                </a>
                            @endguest

                        </div>
                    </div>
                </div>
            @endforeach

        </div>

        {{-- Pagination --}}
        <div class="mt-10 mr-10">
            {{ $products->links() }}
        </div>

    </div>

</body>

</html>