<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    {{-- jQuery --}}
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

    {{-- DataTables --}}
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/dataTables.tailwindcss.min.js"></script>
    <!-- Toastr -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>


    {{-- SweetAlert helpers --}}
    <script>
        function showSweetAlert(message, icon = 'success') {
            Swal.fire({
                toast: true,
                icon: icon,
                title: message,
                position: 'top-end',
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true
            });
        }
        window.alertSuccess = (msg) =>
            Swal.fire({ icon: 'success', text: msg, timer: 1500, showConfirmButton: false });

        window.alertError = (msg) =>
            Swal.fire({ icon: 'error', text: msg });

        window.alertConfirm = (opts) =>
            Swal.fire({
                icon: 'warning',
                title: opts.title,
                text: opts.text,
                showCancelButton: true,
                confirmButtonColor: '#dc2626',
                cancelButtonColor: '#6b7280',
                confirmButtonText: opts.confirmText || 'Yes'
            });

        window.alertLoading = (msg = 'Please wait...') =>
            Swal.fire({ title: msg, allowOutsideClick: false, didOpen: () => Swal.showLoading() });
    </script>
</head>

<body>
    <header class="bg-white border-b border-gray-200 sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex h-16 items-center justify-between">

                <!-- Logo / Title -->
                <div class="flex items-center">
                    <a href="/" class="text-lg sm:text-xl font-bold text-gray-900 hover:text-indigo-600 transition">
                        Product Catalog
                    </a>
                </div>

                <!-- Navigation -->
                <div class="flex items-center space-x-3 sm:space-x-4">
                    @auth
                                <!-- My Orders -->
                                <a href="{{ route('order.view') }}"
                                    class="inline-flex items-center rounded-md bg-gray-100 px-4 py-2 text-sm font-medium text-gray-700 hover:bg-indigo-50 hover:text-indigo-600 transition">
                                    My Orders
                                </a>

                                <!-- Cart -->
                                <a href="{{ route('cart.view') }}"
                                    class="relative inline-flex items-center rounded-md bg-indigo-600 px-4 py-2 text-sm font-medium text-white hover:bg-indigo-500 transition">

                                    Cart

                                    <span id="cart-count" class="absolute -top-2 -right-2 min-w-[20px] text-center bg-red-600 text-black text-xs font-bold rounded-full px-1.5 py-0.5
                           {{ $cartCount > 0 ? '' : 'hidden' }}">
                                        {{ $cartCount }}
                                    </span>

                                </a>

                                <!-- Logout -->
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit"
                                        class="inline-flex items-center rounded-md bg-red-500 px-4 py-2 text-sm font-medium text-white hover:bg-red-600 transition focus:outline-none focus:ring-2 focus:ring-red-400">
                                        Logout
                                    </button>
                                </form>
                    @endauth

                    @guest
                        <!-- Login -->
                        <a href="{{ route('login') }}"
                            class="inline-flex items-center rounded-md bg-gray-100 px-4 py-2 text-sm font-medium text-gray-700 hover:bg-indigo-50 hover:text-indigo-600 transition">
                            Login
                        </a>

                        <!-- Register -->
                        <a href="{{ route('register') }}"
                            class="inline-flex items-center rounded-md bg-indigo-600 px-4 py-2 text-sm font-medium text-white hover:bg-indigo-500 transition">
                            Register
                        </a>
                    @endguest
                </div>
            </div>
        </div>
    </header>
</body>

</html>