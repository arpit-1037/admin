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
            <a href="{{ route('login') }}" class="text-sm text-blue-600 hover:underline">
                Login
            </a>
            <a href="{{ route('register') }}" class="text-sm text-blue-600 hover:underline">
                Register
            </a>
        </div>
    </div>
</header>

<body class="bg-gray-100">

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
                        // fallback if image missing
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
                            {{-- Name + Price --}}
                            <div class="flex items-start justify-between">
                                <h3
                                    class="text-lg font-bold text-gray-900 leading-tight hover:text-indigo-600 transition-colors">
                                    {{ $name }}
                                </h3>

                                <p class="text-lg font-bold text-indigo-600 ml-4 whitespace-nowrap">
                                    ₹{{ number_format($price, 2) }}
                                </p>
                            </div>

                            {{-- Description --}}
                            <p class="mt-1 text-sm text-gray-500 leading-relaxed line-clamp-2">
                                {{ $description }}
                            </p>
                        </div>

                        {{-- Footer --}}
                        <div
                            class="mt-auto pt-3 border-t border-gray-100 flex items-center justify-between text-xs text-gray-400">

                            {{-- Quantity Control (UI only) --}}
                            <div
                                class="qty-control inline-flex items-center rounded-full border border-gray-200 bg-gray-50 shadow-sm overflow-hidden">
                                <button type="button"
                                    class="qty-minus h-7 w-7 flex items-center justify-center text-gray-600 hover:text-indigo-600 hover:bg-white transition-colors">
                                    −
                                </button>

                                <input type="text"
                                    class="qty-input w-8 text-center bg-transparent text-gray-800 font-semibold text-xs"
                                    value="1" readonly>

                                <button type="button"
                                    class="qty-plus h-7 w-7 flex items-center justify-center text-gray-600 hover:text-indigo-600 hover:bg-white transition-colors">
                                    +
                                </button>
                            </div>

                            {{-- Add to Cart (no logic yet) --}}
                            <button type="button"
                                class="add-to-cart-btn inline-flex items-center gap-1 rounded-full bg-indigo-600 px-3 py-1.5 text-xs font-semibold text-white shadow-sm hover:bg-indigo-700 hover:shadow-md transition-all duration-200"
                                data-product-id="{{ $product->id }}">
                                <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M3 3h2l.4 2M7 13h10l3-8H6.4M7 13L5.4 5M7 13l-2 6h14M10 19a1 1 0 11-2 0 1 1 0 012 0zm8 0a1 1 0 11-2 0 1 1 0 012 0z" />
                                </svg>
                                <span>Add to Cart</span>
                            </button>

                        </div>
                    </div>
                </div>
            @endforeach

        </div>

        {{-- Pagination --}}
        <div class="mt-10 mr-10 ">
            {{ $products->links() }}
        </div>

    </div>

</body>

</html>