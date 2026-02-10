<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Orders
        </h2>
    </x-slot>

    <x-slot name="sidebar">
        @include('partials.sidebar')
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
            <div class="flex justify-center mt-6 mb-10 px-4">
                <div class="bg-white shadow-xl rounded-lg w-full max-w-screen-xl">
                    <div class="px-5 py-8">

                        <table id="ordersTable" class="w-full divide-y divide-gray-400 text-base">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-4 text-left font-semibold text-gray-700">#</th>
                                    <th class="px-6 py-4 text-left font-semibold text-gray-700">Name</th>
                                    <th class="px-6 py-4 text-left font-semibold text-gray-700">Email</th>
                                    <th class="px-6 py-4 text-left font-semibold text-gray-700">Total</th>
                                    <th class="px-6 py-4 text-center font-semibold text-gray-700">Status</th>
                                    <th class="px-6 py-4 text-left font-semibold text-gray-700">Payment Intent</th>
                                    <th class="px-6 py-4 text-left font-semibold text-gray-700">Created At</th>
                                    <th class="px-6 py-4 text-center font-semibold text-gray-700">Action</th>
                                </tr>
                            </thead>
                        </table>

                    </div>
                </div>
            </div>

        </div>
    </div>

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
                    ordering: true,
                    responsive: false,

                    order: [[6, 'desc']],

                    language: {
                        search: '',
                        searchPlaceholder: 'Search orders...',
                        processing: 'Loading orders...'
                    },

                    columns: [
                        { data: 'DT_RowIndex', orderable: false, searchable: false },
                        { data: 'name' },
                        { data: 'email' },
                        { data: 'total' },
                        {
                            data: 'status',
                            orderable: false,
                            searchable: false,
                            className: 'text-center'
                        },
                        {
                            data: 'payment_intent_id',
                            orderable: false,
                            searchable: false
                        },
                        { data: 'created_at' },
                        {
                            data: 'action',
                            orderable: false,
                            searchable: false,
                            className: 'text-center'
                        }
                    ]
                });
            });

            // Toggle Order Status
            $(document).on('click', '.toggle-status', function () {

                const orderId = $(this).data('id');

                alertConfirm({
                    title: 'Change order status?',
                    text: 'Do you want to update the order status?',
                    confirmText: 'Yes, update'
                }).then(result => {

                    if (!result.isConfirmed) return;

                    $.ajax({
                        url: `/admin/orders/${orderId}/toggle-status`,
                        type: 'PATCH',
                        data: {
                            _token: '{{ csrf_token() }}'
                        },
                        success: function () {
                            $('#ordersTable').DataTable().ajax.reload(null, false);
                        },
                        error: function (xhr) {
                            alertError(xhr.responseJSON?.message ?? 'Status update failed');
                        }
                    });
                });
            });
        </script>

        {{-- Tailwind Overrides --}}
        <style>
            table.dataTable tbody tr {
                border-top: 1px solid #d1d5db;
            }

            table.dataTable tbody td {
                padding: 1rem 1.5rem;
                vertical-align: middle;
            }

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