<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            User Dashboard
        </h2>
    </x-slot>

    <div class="py-10">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white p-6 rounded shadow">
                <p class="text-gray-700">
                    Welcome, {{ auth()->user()->name }}.
                </p>

                {{-- <div class="mt-6 space-x-4">
                    <a href="{{ route('admin.categories.index') }}" class="text-blue-600">
                        Manage Categories
                    </a>
                    <a href="{{ route('admin.products.index') }}" class="text-blue-600">
                        Manage Products
                    </a>
                </div> --}}
            </div>
        </div>
    </div>
</x-app-layout>
