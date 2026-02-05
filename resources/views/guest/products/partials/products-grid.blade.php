<div class="grid grid-cols-4 gap-6">
    @forelse ($products as $product)
        @php
            $imageUrl = $product->primaryImage
                ? asset('storage/' . $product->primaryImage->path)
                : asset('storage/placeholders/product.svg');
        @endphp

        <div class="product-card bg-white border rounded-2xl shadow-sm hover:shadow-lg transition flex flex-col overflow-hidden">

            {{-- Image --}}
            <div class="aspect-[4/3] bg-gray-100 overflow-hidden">
                <img src="{{ $imageUrl }}" class="h-full w-full object-cover"
                    onerror="this.src='{{ asset('storage/placeholders/product.svg') }}'">
            </div>

            {{-- Content --}}
            <div class="p-4 flex flex-col flex-grow">
                <div class="mb-3">
                    <h3 class="text-lg font-bold text-gray-900">
                        {{ $product->name }}
                    </h3>

                    <p class="text-indigo-600 font-bold">
                        ₹{{ number_format($product->price, 2) }}
                    </p>

                    <p class="text-sm text-gray-500 mt-1 line-clamp-2">
                        {{ $product->description }}
                    </p>
                </div>

                {{-- Footer --}}
                <div class="mt-auto pt-3 border-t flex justify-between items-center">

                    {{-- Quantity Counter --}}
                    <div class="inline-flex items-center border rounded-full bg-gray-50" data-min="1" data-max="10">
                        <button type="button"
                            class="qty-minus h-7 w-7 flex justify-center items-center text-gray-600">−</button>

                        <input type="text"
                            class="e qty-input
                             w-8 text-center bg-transparent text-gray-800 font-semibold text-xs" value="1"
                            readonly>

                        <button type="button"
                            class="qty-plus h-7 w-7 flex justify-center items-center text-gray-600">+</button>
                    </div>

                    {{-- Add to Cart --}}
                    @auth
                        <div class="flex items-center">
                            <input type="hidden" class="qty-hidden" value="1">

                            <button type="button"
                                class="add-to-cart-btn inline-flex items-center gap-1 bg-indigo-600 hover:bg-indigo-700 text-white px-3 py-1.5 rounded-full text-xs font-semibold"
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
    @empty
        <div class="col-span-4 text-center text-gray-500 py-12">
            No products found
        </div>
    @endforelse
</div>

{{-- AJAX PAGINATION (LEGACY STYLE) --}}
@if ($products->lastPage() > 1)
    <div class="flex justify-center gap-2 mt-10">
        @for ($page = 1; $page <= $products->lastPage(); $page++)
            <a href="#" class="pagination-link px-3 py-1 rounded-md border text-sm
                               {{ $page == $products->currentPage()
                    ? 'bg-indigo-600 text-white border-indigo-600'
                    : 'bg-white text-gray-700 hover:bg-gray-100' }}" data-page="{{ $page }}">
                {{ $page }}
            </a>
        @endfor
    </div>
@endif