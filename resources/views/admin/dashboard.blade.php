<x-app-layout>
    {{-- HEADER --}}
    <x-slot name="header">
        <h2 class="font-bold text-2xl text-gray-800 leading-tight">
            Admin Dashboard
        </h2>
    </x-slot>

    {{-- PAGE LAYOUT --}}
    <div class="flex space-y-6 gap-6">

        {{-- SIDEBAR --}}
        <aside class="w-60 bg-white border-r shadow-lg min-h-screen">
            {{-- Sidebar Header --}}
            <div class="p-6 border-b">
                <h3 class="text-lg font-bold text-gray-800">Admin Panel</h3>
                <p class="text-sm text-gray-500 mt-1">Management</p>
            </div>

            {{-- Navigation --}}
            <nav class="p-6 space-y-1 text-gray-700">
                <a href="{{ route('admin.categories.index') }}"
                   class="flex items-center px-4 py-2 rounded-lg hover:bg-blue-50 hover:text-blue-700 transition">
                    📁 <span class="ml-3">Categories</span>
                </a>

                <a href="{{ route('admin.products.index') }}"
                   class="flex items-center px-4 py-2 rounded-lg hover:bg-blue-50 hover:text-blue-700 transition">
                    📦 <span class="ml-3">Products</span>
                </a>

                <a href="{{ route('admin.users.index') }}"
                   class="flex items-center px-4 py-2 rounded-lg hover:bg-blue-50 hover:text-blue-700 transition">
                    👤 <span class="ml-3">Users</span>
                </a>

                <a href="{{ route('admin.orders.index') }}"
                   class="flex items-center px-4 py-2 rounded-lg hover:bg-blue-50 hover:text-blue-700 transition">
                    🧾 <span class="ml-3">Orders</span>
                </a>
            </nav>
        </aside>

        {{-- MAIN CONTENT --}}
        <main class="flex-1 p-8">
            <div class="max-w-50rem space-y-1 gap-6">

                {{-- Welcome --}}
                <div class="ml-8 bg-gray-50 shadow rounded-lg p-6">
                    <h3 class="text-xl font-semibold text-gray-800">
                        Welcome, {{ auth()->user()->name }}
                    </h3>
                    <p class="text-sm text-gray-500">
                        You are logged in as an administrator.
                    </p>
                </div>

                {{-- Dashboard Cards --}}
                {{-- <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                    <div class="bg-white shadow rounded-lg p-6">
                        <h4 class="text-lg font-semibold mb-2">Category Management</h4>
                        <p class="text-sm text-gray-600 mb-4">
                            Create and manage categories.
                        </p>
                        <div class="flex gap-3">
                            <a href="{{ route('admin.categories.index') }}" class="admin-btn">View</a>
                            <a href="{{ route('admin.categories.create') }}" class="admin-btn">Add</a>
                        </div>
                    </div>

                    <div class="bg-white shadow rounded-lg p-6">
                        <h4 class="text-lg font-semibold mb-2">Product Management</h4>
                        <p class="text-sm text-gray-600 mb-4">
                            Manage products and stock.
                        </p>
                        <div class="flex gap-3">
                            <a href="{{ route('admin.products.index') }}" class="admin-btn">View</a>
                            <a href="{{ route('admin.products.create') }}" class="admin-btn">Add</a>
                        </div>
                    </div>

                    <div class="bg-white shadow rounded-lg p-6">
                        <h4 class="text-lg font-semibold mb-2">User Management</h4>
                        <p class="text-sm text-gray-600 mb-4">
                            Manage system users.
                        </p>
                        <a href="{{ route('admin.users.index') }}" class="admin-btn">Users</a>
                    </div>

                    <div class="bg-white shadow rounded-lg p-6">
                        <h4 class="text-lg font-semibold mb-2">Order Management</h4>
                        <p class="text-sm text-gray-600 mb-4">
                            View and manage orders.
                        </p>
                        <a href="{{ route('admin.orders.index') }}" class="admin-btn">Orders</a>
                    </div>

                </div> --}}
            </div>
        </main>
    </div>
</x-app-layout>
