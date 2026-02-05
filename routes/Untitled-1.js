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
        let currentPage = 1;
        let currentSearch = '';
        let currentCategory = 0;

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

        $(document).off('click', '.add-to-cart-btn').on('click', '.add-to-cart-btn', function (e) {
            e.preventDefault();
            const $btn = $(this);
            const productId = $btn.data('product-id');
            // alert(productId);

            // find quantity from nearest product card's .counter
            const $productCard = $btn.closest('.group');
            let qty = 1;
            qty = parseInt($productCard.find('.qty-input').val(), 10);
            // disable button immediately to prevent accidental double clicks
            if ($btn.prop('disabled')) return;
            $btn.prop('disabled', true);

            $.ajax({
                url: '../public/index.php?page=cart',
                method: 'POST',
                dataType: 'json',
                data: {
                    action: 'add',
                    product_id: productId,
                    quantity: qty,

                },
                success: function (res) {
                    // console.log(res);    
                    // alert(res.cart_count);
                    // alert('res');
                    document.getElementById("couu").innerHTML = res.cart_count;
                    if (res && res.success) {
                        if (typeof showSweetAlert === 'function') {
                            showSweetAlert(res.message || 'Added to cart', 'success');
                        }
                        if (res.cart_count !== undefined) {
                            $('#cartCountBadge').text(res.cart_count);
                        }
                    } else {
                        if (typeof showSweetAlert === 'function') {
                            showSweetAlert(res.message || 'Could not add to cart', 'error');
                        }
                    }
                },
                error: function () {
                    if (typeof showSweetAlert === 'function') {
                        showSweetAlert('Server error while adding to cart', 'error');
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

        document.addEventListener('click', function (e) {
            if (e.target.classList.contains('qty-plus')) {
                // alert('hello');
                updateQty(e.target, 1);
            }
            if (e.target.classList.contains('qty-minus')) {
                updateQty(e.target, -1);
            }
        });
    </script>