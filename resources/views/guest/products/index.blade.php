@extends('layouts.user')
@section('title', 'User Dashboard')
@push('scripts')
    <script>
        document.addEventListener('input', function (e) {
            if (!e.target.classList.contains('qty-input')) return;

            let value = e.target.value.replace(/\D/g, ''); // numbers only

            if (value.length > 3) {
                value = value.slice(0, 3); // max 3 digits
            }

            e.target.value = value || 1;
        });
        /* -----------------------------
         * Quantity Controls
         * ----------------------------- */
        function updateQty(button, delta) {
            const card = button.closest('.product-card');
            const input = card.querySelector('.qty-input');

            let qty = parseInt(input.value, 10);
            qty = isNaN(qty) ? 1 : qty;

            qty += delta;
            if (qty < 1) qty = 1;
            if (qty > 99) qty = 99;

            input.value = qty;
        }

        document.addEventListener('click', function (e) {
            if (e.target.classList.contains('qty-plus')) {
                updateQty(e.target, 1);
            }
            if (e.target.classList.contains('qty-minus')) {
                updateQty(e.target, -1);
            }
        });

        /* -----------------------------
         * Cart Count Update
         * ----------------------------- */
        function updateCartCount(count) {
            const badge = document.getElementById('cart-count');
            if (!badge) return;

            if (count > 0) {
                badge.textContent = count;
                badge.classList.remove('hidden');
            } else {
                badge.classList.add('hidden');
            }
        }

        /* -----------------------------
         * Global State
         * ----------------------------- */
        let currentPage = 1;
        let currentSearch = '';
        let currentCategory = 0;

        /* -----------------------------
         * Fetch Products (AJAX)
         * ----------------------------- */
        function fetchProducts(page = 1) {
            currentPage = page;

            fetch(window.location.pathname + '?' + new URLSearchParams({
                ajax: 1,
                search: currentSearch,
                category_id: currentCategory,
                page_num: currentPage
            }), {
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            })
                .then(res => res.json())
                .then(data => {
                    document.getElementById('products-wrapper').innerHTML = data.html;
                    document.getElementById('count-product').textContent = data.count;
                })
                .catch(() => alert('Failed to load products'));
        }

        /* -----------------------------
         * DOM Ready
         * ----------------------------- */
        document.addEventListener('DOMContentLoaded', function () {

            /* 🛒 Add to Cart */
            $(document)
                .off('click', '.add-to-cart-btn')
                .on('click', '.add-to-cart-btn', function (e) {
                    e.preventDefault();

                    const $btn = $(this);
                    if ($btn.prop('disabled')) return;

                    const productId = $btn.data('product-id');
                    const $productCard = $btn.closest('.product-card');

                    let qty = parseInt($productCard.find('.qty-input').val(), 10);
                    qty = isNaN(qty) || qty < 1 ? 1 : qty;

                    $btn.prop('disabled', true);

                    $.ajax({
                        url: '{{ route('cart.action') }}',
                        method: 'POST',
                        dataType: 'json',
                        data: {
                            _token: '{{ csrf_token() }}',
                            action: 'add',
                            product_id: productId,
                            quantity: qty
                        },
                        success: function (res) {
                            if (res && res.success) {
                                updateCartCount(res.cart_count);
                                if (typeof showSweetAlert === 'function') {
                                    showSweetAlert(res.message || 'Added to cart');
                                }
                            } else {
                                if (typeof alertError === 'function') {
                                    alertError(res.message || 'Could not add to cart');
                                }
                            }
                        },
                        error: function (xhr) {

                            if (xhr.responseJSON && xhr.responseJSON.message) {

                                Swal.fire({
                                    icon: 'warning',
                                    title: 'Stock Limit',
                                    text: xhr.responseJSON.message
                                });

                            } else {

                                Swal.fire({
                                    icon: 'error',
                                    title: 'Server Error',
                                    text: 'Unexpected server error.'
                                });

                            }
                        },
                        complete: function () {
                            // 🔥 IMPORTANT FIX — always re-enable
                            $btn.prop('disabled', false);
                        }
                    });
                });

            /* 🔍 Search (debounced) */
            const searchInput = document.getElementById('search-input');
            let debounceTimer = null;

            searchInput.addEventListener('keyup', function () {
                clearTimeout(debounceTimer);
                debounceTimer = setTimeout(() => {
                    currentSearch = searchInput.value.trim();
                    fetchProducts(1);
                }, 400);
            });

            /* 📂 Category Filter */
            document.getElementById('category-select').addEventListener('change', function () {
                currentCategory = this.value;
                fetchProducts(1);
            });

        });

        /* -----------------------------
         * Pagination
         * ----------------------------- */
        document.addEventListener('click', function (e) {
            const link = e.target.closest('.pagination-link');
            if (!link) return;

            e.preventDefault();
            fetchProducts(link.dataset.page);
        });
    </script>
@endpush
@section('content')

    <body class="bg-gray-100">

        <div class="max-w-7xl mx-auto px-4 py-8">

            <div class="flex items-center gap-4 mb-6 ml-4">
                <!-- LEFT -->
                <h2 class="text-xl font-semibold">
                    Products (<span id="count-product">{{ $products->total() }}</span>)
                </h2>

                <!-- RIGHT -->
                <div class="flex items-center gap-4 ml-auto">
                    <!-- 🔍 SEARCH -->
                    <input type="text" id="search-input" placeholder="Search..." class="border px-3 py-2 rounded w-64">

                    <!-- 📂 CATEGORY -->
                    <select id="category-select" class="border px-3 py-2 rounded">
                        <option value="0">All Categories</option>

                        @foreach ($cat->whereNull('parent_id') as $parent)
                            <!-- Parent (selectable) -->
                            <option value="{{ $parent->id }}" class="font-semibold">
                                {{ $parent->name }}
                            </option>

                            <!-- Children -->
                            @foreach ($cat->where('parent_id', $parent->id) as $child)
                                <option value="{{ $child->id }}">
                                    └─ {{ $child->name }}
                                </option>
                            @endforeach
                        @endforeach
                    </select>
                </div>
            </div>

            <!-- PRODUCTS -->
            <div id="products-wrapper">
                @include('guest.products.partials.products-grid')
            </div>
        </div>
        @if (session('welcome_message'))

            <script>
                document.addEventListener('DOMContentLoaded', function () {
                    showSweetAlert(@json(session('welcome_message')));
                });
            </script>
        @endif

        @if (session('Welcome_new_user'))

            <script>
                document.addEventListener('DOMContentLoaded', function () {
                    showSweetAlert(@json(session('Welcome_new_user')));
                });
            </script>
        @endif


    </body>
@endsection