@extends('layouts.user')
@section('title', 'User Dashboard')
@push('scripts')
<script>
    function updateQty(button, delta) {
        const card = button.closest('.product-card');
        const input = card.querySelector('.qty-input');
        // alert(input.value);
        let qty = parseInt(input.value, 10);
        qty = isNaN(qty) ? 1 : qty;

        qty += delta;
        // alert(qty);
        if (qty < 1) qty = 1;
        if (qty > 99) qty = 99;

        input.value = qty;
    }

    document.addEventListener('click', function (e) {
        if (e.target.classList.contains('qty-plus')) {
            // alert('hello');
            updateQty(e.target, 1);
        }
        if (e.target.classList.contains('qty-minus')) {
            updateQty(e.target, -1);
        }
    });


    let currentPage = 1;
    let currentSearch = '';
    let currentCategory = 0;



    document.addEventListener('DOMContentLoaded', function () {
        // Initial fetch
        $(document).off('click', '.add-to-cart-btn').on('click', '.add-to-cart-btn', function (e) {
            e.preventDefault();
            // alert('mmj');
            const $btn = $(this);
            const productId = $btn.data('product-id');
            // alert(productId);

            // find quantity from nearest product card's .counter
            const $productCard = $btn.closest('.product-card');
            // alert($productCard.find('.qty-input').val());
            let qty = 1;
            qty = parseInt($productCard.find('.qty-input').val(), 10);
            // alert(qty);
            // disable button immediately to prevent accidental double clicks
            if ($btn.prop('disabled')) return;
            $btn.prop('disabled', true);

            $.ajax({

                url: '{{ route('cart.action') }}',
                method: 'POST',
                dataType: 'json',
                data: {
                    _token: '{{ csrf_token() }}',
                    action: 'add',
                    product_id: productId,
                    quantity: qty,

                },
                success: function (res) {
                    // console.log(res);        
                    // alert(res.cart_count);   
                    // alert(res);
                    document.getElementById("cart-count").textContent = res.cart_count;
                    if (res && res.success) {
                        if (typeof alertSuccess === 'function') {
                            showSweetAlert(res.message || 'Added to cart');
                        }
                        if (res.cart_count !== undefined) {
                            $('#cartCountBadge').text(res.cart_count);
                        }
                    } else {
                        if (typeof alertError === 'function') {
                            alertError(res.message || 'Could not add to cart');
                        }
                    }
                },
                error: function () {
                    if (typeof alertError === 'function') {
                        alertError('Server error while adding to cart');
                    }
                },
                complete: function () {
                    // re-enable after short delay to avoid immediate re-click
                    setTimeout(function () {
                        $btn.prop('disabled', false);
                    }, 600);
                }
            });
        });
    });

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
    // 🔍 SEARCH (debounced)
    const searchInput = document.getElementById('search-input');
    let debounceTimer = null;

    searchInput.addEventListener('keyup', function () {
        clearTimeout(debounceTimer);
        debounceTimer = setTimeout(() => {
            currentSearch = searchInput.value.trim();
            fetchProducts(1);
        }, 400);
    });

    // 📂 CATEGORY FILTER
    document.getElementById('category-select').addEventListener('change', function () {
        currentCategory = this.value;
        fetchProducts(1);
    });

    // 📄 PAGINATION
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
                        @foreach ($cat as $category)
                            <option value="{{ $category->id }}">
                                {{ $category->name }}
                            </option>
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