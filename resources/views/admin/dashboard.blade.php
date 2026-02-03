<x-app-layout>
    {{-- HEADER --}}
    <x-slot name="header">
        <div class="flex items-center gap-4">
            <button id="sidebarToggle"
                class="p-2 rounded-lg bg-gray-200 hover:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500">
                ☰
            </button>

            <h2 class="font-bold text-2xl text-gray-800 leading-tight">
                Admin Dashboard
            </h2>
        </div>
    </x-slot>

    {{-- PAGE WRAPPER --}}
    <div class="relative min-h-screen bg-gray-100">

        {{-- OVERLAY --}}
        <div id="sidebarOverlay" class="fixed inset-0 bg-black/40 z-30 hidden">
        </div>

        {{-- SIDEBAR --}}
        <aside id="sidebar" class="fixed inset-y-0 left-0 z-40 w-64 bg-white border-r shadow-lg hidden">

            {{-- Sidebar Header --}}
            <div class="p-6 border-b">
                <h3 class="text-lg font-bold text-gray-800">Admin Panel</h3>
                <p class="text-sm text-gray-500 mt-1">Management</p>
            </div>

            {{-- Navigation --}}
            <nav class="p-4 space-y-1 text-gray-700">

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
        <main class="py-10 px-6">
            <div class="max-w-7xl mx-auto space-y-6">

                {{-- Welcome --}}
                <div class="bg-white shadow rounded-lg p-6">
                    <h3 class="text-xl font-semibold text-gray-800">
                        Welcome, {{ auth()->user()->name }}
                    </h3>
                    <p class="text-sm text-gray-500">
                        You are logged in as an administrator.
                    </p>
                </div>

                {{-- Management Cards --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                    <div class="bg-white shadow rounded-lg hover:shadow-md transition">
                        <div class="p-6">
                            <h4 class="text-lg font-semibold mb-2">Category Management</h4>
                            <p class="text-sm text-gray-600 mb-4">
                                Create and manage categories.
                            </p>
                            <div class="flex gap-3">
                                <a href="{{ route('admin.categories.index') }}" class="admin-btn">View</a>
                                <a href="{{ route('admin.categories.create') }}" class="admin-btn">Add</a>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white shadow rounded-lg hover:shadow-md transition">
                        <div class="p-6">
                            <h4 class="text-lg font-semibold mb-2">Product Management</h4>
                            <p class="text-sm text-gray-600 mb-4">
                                Manage products and stock.
                            </p>
                            <div class="flex gap-3">
                                <a href="{{ route('admin.products.index') }}" class="admin-btn">View</a>
                                <a href="{{ route('admin.products.create') }}" class="admin-btn">Add</a>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white shadow rounded-lg hover:shadow-md transition">
                        <div class="p-6">
                            <h4 class="text-lg font-semibold mb-2">User Management</h4>
                            <p class="text-sm text-gray-600 mb-4">
                                Manage system users.
                            </p>
                            <a href="{{ route('admin.users.index') }}" class="admin-btn">Users</a>
                        </div>
                    </div>

                    <div class="bg-white shadow rounded-lg hover:shadow-md transition">
                        <div class="p-6">
                            <h4 class="text-lg font-semibold mb-2">Order Management</h4>
                            <p class="text-sm text-gray-600 mb-4">
                                View and manage orders.
                            </p>
                            <a href="{{ route('admin.orders.index') }}" class="admin-btn">Orders</a>
                        </div>
                    </div>

                </div>
            </div>
        </main>
    </div>

    {{-- TOGGLE SCRIPT --}}
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const toggle = document.getElementById('sidebarToggle');
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('sidebarOverlay');

            toggle.addEventListener('click', function () {
                sidebar.classList.toggle('hidden');
                overlay.classList.toggle('hidden');
            });

            overlay.addEventListener('click', function () {
                sidebar.classList.add('hidden');
                overlay.classList.add('hidden');
            });
        });
    </script>
</x-app-layout>