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