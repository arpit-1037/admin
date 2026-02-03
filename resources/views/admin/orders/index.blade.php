<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Orders
        </h2>
    </x-slot>

    <div class="py-10">
        <div class="max-w-9xl mx-auto sm:px-6 lg:px-10">

            {{-- Action Bar --}}
            <div class="flex justify-between items-center mb-6">
                <p class="text-gray-600">
                    Manage all orders in the system
                </p>
            </div>

            {{-- Table Card --}}
            <div class="flex justify-center mt-10 mb-10 px-4">
                <div class="bg-white shadow-xl rounded-lg w-full max-w-screen-xl">
                    <div class="px-5 py-8">

                        <table id="ordersTable" class="min-w-full divide-y divide-gray-200 text-base">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-4 text-left font-semibold text-gray-700">#</th>
                                    <th class="px-6 py-4 text-left font-semibold text-gray-700">User ID</th>
                                    <th class="px-6 py-4 text-left font-semibold text-gray-700">Total</th>
                                    <th class="px-6 py-4 text-center font-semibold text-gray-700">Status</th>
                                    <th class="px-6 py-4 text-left font-semibold text-gray-700">Payment Intent</th>
                                    <th class="px-6 py-4 text-left font-semibold text-gray-700">Created At</th>
                                </tr>
                            </thead>
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
                $('#ordersTable').DataTable({
                    processing: true,
                    serverSide: true,
                    ajax: "{{ route('admin.orders.index') }}",
                    pageLength: 10,
                    lengthChange: false,
                    responsive: true,

                    // created_at column index = 5
                    order: [[5, 'desc']],

                    language: {
                        search: '',
                        searchPlaceholder: 'Search orders...',
                        processing: 'Loading orders...'
                    },

                    columns: [
                        { data: 'DT_RowIndex', orderable: false, searchable: false },
                        { data: 'user_id' },
                        { data: 'total' },
                        {
                            data: 'status',
                            orderable: false,
                            searchable: false,
                            className: 'text-center',
                            render: function (data) {
                                let color =
                                    data === 'pending' ? 'bg-yellow-100 text-yellow-700' :
                                        data === 'completed' ? 'bg-green-100 text-green-700' :
                                            'bg-red-100 text-red-700';

                                return `<span class="px-3 py-1 rounded-full text-sm font-medium ${color}">
                                                ${data}
                                            </span>`;
                            }
                        },
                        {
                            data: 'payment_intent_id',
                            render: function (data) {
                                return data ?? '<span class="text-gray-400">—</span>';
                            }
                        },
                        { data: 'created_at' }
                    ]
                });
            });
        </script>

        {{-- Tailwind Overrides (SAME AS ALL OTHER TABLES) --}}
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