<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Categories
        </h2>
    </x-slot>

    <div class="py-10">
        <div class="max-w-9xl mx-auto sm:px-6 lg:px-10">

            {{-- Action Bar --}}
            <div class="flex justify-between items-center mb-6">
                <p class="text-gray-600">
                    Manage all categories in the system
                </p>

                <a href="{{ route('admin.categories.create') }}" class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md
                          font-semibold text-xs text-black uppercase tracking-widest hover:bg-green-700 mb-6
                          focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2">
                    Add Category
                </a>
            </div>

            {{-- Table Card --}}
            <div class="flex justify-center mt-10 mb-10 px-4">
                <div class="bg-white shadow-xl rounded-lg w-full max-w-screen-xl">
                    <div class="px-5 py-8">

                        <table id="categoriesTable" class="w-full divide-y divide-gray-400 text-base p-15">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-4 text-left font-semibold text-gray-700">#</th>
                                    <th class="px-6 py-4 text-left font-semibold text-gray-700">Name</th>
                                    <th class="px-6 py-4 text-center font-semibold text-gray-700">Status</th>
                                    <th class="px-6 py-4 text-center font-semibold text-gray-700">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($categories as $index => $category)
                                    <tr class="border-t">
                                        <td class="px-6 py-4">
                                            {{ $index + 1 }}
                                        </td>

                                        <td class="px-6 py-4">
                                            {{ $category->name }}
                                        </td>

                                        <td class="px-6 py-4 text-center">
                                            <span
                                                class="px-3 py-1 rounded-full text-sm font-medium
                                                    {{ $category->is_active ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                                                {{ $category->is_active ? 'Active' : 'Inactive' }}
                                            </span>
                                        </td>

                                        <td class="px-6 py-4 text-center space-x-3">
                                            <a href="{{ route('admin.categories.edit', $category) }}"
                                                class="text-blue-600 hover:underline">
                                                Edit
                                            </a>

                                            <form method="POST" action="{{ route('admin.categories.destroy', $category) }}"
                                                class="inline">
                                                @csrf
                                                @method('DELETE')

                                                <button type="submit" class="text-red-600 hover:underline"
                                                    onclick="return confirm('Are you sure?')">
                                                    Delete
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>

                    </div>
                </div>
            </div>

        </div>
    </div>

    {{-- Page Scripts --}}
    @push('scripts')

        {{-- jQuery --}}
        <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

        {{-- DataTables --}}
        <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
        <script src="https://cdn.datatables.net/1.13.7/js/dataTables.tailwindcss.min.js"></script>

        {{-- DataTable Init --}}
        <script>
            $(document).ready(function () {
                $('#categoriesTable').DataTable({
                    pageLength: 10,
                    lengthChange: false,
                    ordering: true,
                    language: {
                        search: '',
                        searchPlaceholder: 'Search categories...'
                    }
                });
            });
        </script>

        {{-- DataTables Tailwind Styling Overrides (SAME AS PRODUCTS) --}}
        <style>
            .dataTables_filter input {
                border: 1px solid #d1d5db;
                border-radius: 0.375rem;
                padding: 6px 10px;
                margin-left: 8px;
            }

            .dataTables_wrapper .dataTables_paginate .paginate_button {
                padding: 4px 10px;
                margin: 0 2px;
                border-radius: 0.375rem;
                border: 1px solid #d1d5db;
                color: #374151 !important;
            }

            .dataTables_wrapper .dataTables_paginate .paginate_button.current {
                background: #16a34a !important;
                color: white !important;
                border-color: #16a34a;
            }

            .dataTables_wrapper .dataTables_paginate .paginate_button:hover {
                background: #dcfce7 !important;
                color: #065f46 !important;
            }
        </style>

    @endpush
</x-app-layout>