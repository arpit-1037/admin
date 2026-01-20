<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Admin Dashboard
        </h2>
    </x-slot>

    <div class="py-10">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            {{-- Welcome --}}
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-semibold mb-1">
                        Welcome, {{ auth()->user()->name }}
                    </h3>
                    <p class="text-gray-600">
                        You are logged in as an administrator.
                    </p>
                </div>
            </div>

            {{-- Management Cards --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                {{-- Category Management (ACTIVE) --}}
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h4 class="text-md font-semibold mb-2">
                            Category Management
                        </h4>

                        <p class="text-gray-600 mb-4">
                            Create, update, and manage categories used across the system.
                        </p>

                        <div class="flex gap-3">
                            <a href="{{ route('admin.categories.index') }}"
                                class="bg-blue-600 text-black px-4 py-2 rounded hover:bg-blue-700">
                                View Categories
                            </a>

                            <a href="{{ route('admin.categories.create') }}"
                                class="bg-green-600 text-black px-4 py-2 rounded hover:bg-green-700">
                                Add Category
                            </a>
                        </div>
                    </div>
                </div>

                {{-- Product Management (INACTIVE) --}}
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h4 class="text-md font-semibold mb-2">
                            Product Management
                        </h4>

                        <p class="text-gray-600 mb-4">
                            Manage products, pricing, stock, and images.
                        </p>

                        <div class="flex gap-3">
                            <a href="{{ route('admin.products.index') }}"
                                class="bg-blue-600 text-black px-4 py-2 rounded hover:bg-blue-700">
                                View Products
                            </a>

                            <a href="{{ route('admin.products.create') }}"
                                class="bg-green-600 text-black px-4 py-2 rounded hover:bg-green-700">
                                Add Product
                            </a>
                        </div>
                    </div>
                </div>

            </div>

        </div>
    </div>
</x-app-layout>