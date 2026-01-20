<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Products
        </h2>
    </x-slot>

    <div class="py-10">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            {{-- Action Bar --}}
            <div class="flex justify-between items-center mb-6">
                <p class="text-gray-600">
                    Manage all products in the system
                </p>

                <a href="{{ route('admin.products.create') }}"
                   class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">
                    Add Product
                </a>
            </div>

            {{-- Product Table --}}
            <div class="bg-white shadow-sm sm:rounded-lg p-6">
                <div class="overflow-x-auto">
                    <table id="productsTable" class="w-full table-auto border-collapse">
                        <thead>
                            <tr class="border-b text-left text-gray-600">
                                <th class="py-3 px-2">#</th>
                                <th class="py-3 px-2">Image</th>
                                <th class="py-3 px-2">Name</th>
                                <th class="py-3 px-2">Category</th>
                                <th class="py-3 px-2">Price</th>
                                <th class="py-3 px-2">Stock</th>
                                <th class="py-3 px-2">Status</th>
                                <th class="py-3 px-2">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($products as $product)
                                <tr class="border-b hover:bg-gray-50">
                                    <td class="py-3 px-2">
                                        {{ $loop->iteration }}
                                    </td>

                                    <td class="py-3 px-2">
                                        @if($product->primaryImage)
                                            <img src="{{ asset('storage/'.$product->primaryImage->path) }}"
                                                 class="h-12 w-12 rounded object-cover">
                                        @else
                                            <span class="text-gray-400 text-sm">No Image</span>
                                        @endif
                                    </td>

                                    <td class="py-3 px-2 font-medium">
                                        {{ $product->name }}
                                    </td>

                                    <td class="py-3 px-2">
                                        {{ $product->category->name }}
                                    </td>

                                    <td class="py-3 px-2">
                                        ₹{{ number_format($product->price, 2) }}
                                    </td>

                                    <td class="py-3 px-2">
                                        {{ $product->stock }}
                                    </td>

                                    <td class="py-3 px-2">
                                        @if($product->is_active)
                                            <span class="text-green-600 font-semibold">Active</span>
                                        @else
                                            <span class="text-red-600 font-semibold">Inactive</span>
                                        @endif
                                    </td>

                                    <td class="py-3 px-2">
                                        <div class="flex gap-3">
                                            {{-- Edit (future-ready) --}}
                                            <a href="#"
                                               class="text-blue-600 hover:underline">
                                                Edit
                                            </a>

                                            {{-- Delete --}}
                                            <form method="POST"
                                                  action="{{ route('admin.products.destroy', $product) }}"
                                                  onsubmit="return confirm('Are you sure you want to delete this product?')">
                                                @csrf
                                                @method('DELETE')

                                                <button type="submit"
                                                        class="text-red-600 hover:underline">
                                                    Delete
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>

    {{-- DataTables Scripts --}}
    @push('scripts')
        <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
        <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
        <script src="https://cdn.datatables.net/1.13.7/js/dataTables.tailwindcss.min.js"></script>

        <script>
            $(document).ready(function () {
                $('#productsTable').DataTable({
                    pageLength: 10,
                    lengthChange: false,
                    ordering: true,
                    language: {
                        search: "Search products:"
                    }
                });
            });
        </script>
    @endpush
</x-app-layout>
