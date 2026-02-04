<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- App Assets -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/dataTables.tailwindcss.min.css">


    <!-- Livewire Styles -->
    @livewireStyles
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        window.alertSuccess = function (message, title = 'Success') {
            Swal.fire({
                icon: 'success',
                title: title,
                text: message,
                timer: 1600,
                showConfirmButton: false
            });
        };

        window.alertError = function (message, title = 'Error') {
            Swal.fire({
                icon: 'error',
                title: title,
                text: message
            });
        };

        window.alertConfirm = function ({ title, text, confirmText = 'Yes' }) {
            return Swal.fire({
                icon: 'warning',
                title: title,
                text: text,
                showCancelButton: true,
                confirmButtonColor: '#dc2626',
                cancelButtonColor: '#6b7280',
                confirmButtonText: confirmText
            });
        };

        window.alertLoading = function (message = 'Please wait...') {
            Swal.fire({
                title: message,
                allowOutsideClick: false,
                didOpen: () => Swal.showLoading()
            });
        };
    </script>


</head>

<body class="font-sans antialiased">
    <x-banner />

    <div class="min-h-screen bg-gray-100">
        @livewire('navigation-menu')

        {{-- Page Header --}}
        @if (isset($header))
            <header class="bg-white shadow">
                <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                    {{ $header }}
                </div>
            </header>
        @endif

        {{-- Page Content --}}
        <main>
            {{ $slot }}
        </main>
    </div>

    {{-- Modals --}}
    @stack('modals')
 
    {{-- REQUIRED: Page-level scripts (DataTables, etc.) --}}
    @stack('scripts')

    {{-- Livewire Scripts --}}
    @livewireScripts
    
</body>

</html>