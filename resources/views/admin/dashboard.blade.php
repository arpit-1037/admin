<x-app-layout>
    <x-slot name="sidebar">
        @include('partials.sidebar')
    </x-slot>

    {{-- MAIN CONTENT --}}
    <div class="flex-1 p-6">

        {{-- Welcome --}}
        <div class="bg-gray-50 shadow rounded-lg p-6 mt-8 mb-6">
            <h3 class="text-xl font-semibold text-gray-800">
                Welcome, {{ auth()->user()->name }}
            </h3>
            <p class="text-sm text-gray-500">
                You are logged in as an administrator.
            </p>
        </div>

    </div>
    @if(session('welcome_message'))
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                showSweetAlert(@json(session('welcome_message')));
            });
        </script>
    @endif

</x-app-layout>