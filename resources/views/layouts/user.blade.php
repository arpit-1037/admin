<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'User Panel')</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')
</head>
<body>

    @include('partials.user-header')

    <main class="p-6">
        @yield('content')
    </main>

    @stack('scripts')
 
</body>

</html>