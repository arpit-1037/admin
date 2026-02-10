<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    {{-- Meta --}}
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    {{-- Fonts --}}
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    {{-- App CSS & JS --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    {{-- jQuery (MUST be first) --}}
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

    {{-- DataTables CSS --}}
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/dataTables.tailwindcss.min.css">

    {{-- DataTables JS --}}
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/dataTables.tailwindcss.min.js"></script>

    {{-- Toastr --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    {{-- Livewire --}}
    @livewireStyles

    {{-- Page-level styles --}}
    @stack('styles')
    <script src="https://cdn.tailwindcss.com"></script>

    {{-- SweetAlert2 --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        window.confirmDelete = function (button) {
            const form = button.closest('form');

            alertConfirm({
                title: 'Are you sure?',
                text: 'This action cannot be undone',
                confirmText: 'Delete'
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        };

        // document.addEventListener('click', function () {
        //     if (Swal.isVisible()) {
        //         Swal.close();
        //     }
        // });
    </script>


    {{-- Global Alert Helpers (ONCE) --}}
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
        window.alertSuccess = (message, title = 'Success') =>
            Swal.fire({ icon: 'success', title, text: message, timer: 1600, showConfirmButton: false });

        window.alertError = (message, title = 'Error') =>
            Swal.fire({ icon: 'error', title, text: message });

        window.alertConfirm = ({ title, text, confirmText = 'Yes' }) =>
            Swal.fire({
                icon: 'warning',
                title,
                text,
                showCancelButton: true,
                confirmButtonColor: '#dc2626',
                cancelButtonColor: '#6b7280',
                confirmButtonText: confirmText
            });

        window.alertLoading = (message = 'Please wait...') =>
            Swal.fire({
                title: message,
                allowOutsideClick: false,
                didOpen: () => Swal.showLoading()
            });
    </script>
</head>

<body class="font-sans antialiased bg-gray-100">

    <x-banner />

    <div class="min-h-screen flex">

        {{-- Sidebar (optional per layout) --}}
        @isset($sidebar)
            {{ $sidebar }}
        @endisset

        {{-- Main content --}}
        <div class="flex-1 flex flex-col">

            {{-- Navigation --}}
            @livewire('navigation-menu')

            {{-- Page header --}}
            @isset($header)
                <header class="bg-white shadow">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endisset

            {{-- Page content --}}
            <main class="flex-1">
                {{ $slot }}
            </main>

        </div>
    </div>

    {{-- Modals --}}
    @stack('modals')

    {{-- Page-specific scripts --}}
    @stack('scripts')

    {{-- Livewire --}}
    @livewireScripts

</body>

</html>