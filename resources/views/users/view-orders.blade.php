@extends('layouts.user')

@section('title', 'My Orders')

@push('scripts')
    <script>
        $(document).ready(function () {
            $('#ordersTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('user.orders.index') }}",
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
@endpush
@push('styles')
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

@section('content')
    <div class="py-10">
        <div class="max-w-9xl mx-auto sm:px-6 lg:px-10">

            {{-- Action Bar --}}
            <div class="flex justify-between items-center mb-6">
                <h1 class="text-gray-600">
                    All Past orders
                </h1>
            </div>

            {{-- Table Card --}}
            <div class="flex justify-center mt-10 mb-10 px-4">
                <div class="bg-white shadow-xl rounded-lg w-full max-w-screen-xl">
                    <div class="px-5 py-8">

                        <table id="ordersTable" class="min-w-full divide-y divide-gray-200 text-base">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-4 text-left font-semibold text-gray-700">sr. no</th>
                                    <th class="px-6 py-4 text-left font-semibold text-gray-700">your Order ID</th>
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

@endsection