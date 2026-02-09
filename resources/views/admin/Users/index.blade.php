<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Users
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
                    Manage all registered users
                </p>
            </div>

            {{-- Table Card --}}
            <div class="flex justify-center mt-6 mb-10 px-4">
                <div class="bg-white shadow-xl rounded-lg w-full max-w-screen-xl">
                    <div class="px-5 py-8">

                        <table id="usersTable" class="w-full divide-y divide-gray-400 text-base">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-4 text-left font-semibold text-gray-700">#</th>
                                    <th class="px-6 py-4 text-left font-semibold text-gray-700">Name</th>
                                    <th class="px-6 py-4 text-left font-semibold text-gray-700">Email</th>
                                    <th class="px-6 py-4 text-center font-semibold text-gray-700">Status</th>
                                    <th class="px-6 py-4 text-left font-semibold text-gray-700">Registered On</th>
                                    <th class="px-6 py-4 text-center font-semibold text-gray-700">Actions</th>
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
                $('#usersTable').DataTable({
                    processing: true,
                    serverSide: true,
                    ajax: "{{ route('admin.users.index') }}",
                    pageLength: 10,
                    lengthChange: false,
                    responsive: false,
                    order: [[1, 'asc']],

                    language: {
                        search: '',
                        searchPlaceholder: 'Search users...'
                    },

                    columns: [
                        { data: 'DT_RowIndex', orderable: false, searchable: false },
                        { data: 'name' },
                        { data: 'email' },
                        {
                            data: 'status',
                            orderable: false,
                            searchable: false,
                            className: 'text-center'
                        },
                        { data: 'registered_on' },
                        {
                            data: 'actions',
                            orderable: false,
                            searchable: false,
                            className: 'text-center'
                        }
                    ]
                });
            });

            $(document).on('click', '.toggle-status', function () {

                let button = $(this);
                let userId = button.data('id');

                alertConfirm({
                    title: 'Are you sure?',
                    text: 'Do you want to change user status?',
                    confirmText: 'Yes, change it'
                }).then((result) => {

                    if (!result.isConfirmed) return;

                    $.ajax({
                        url: `/admin/users/${userId}/toggle-status`,
                        type: 'PATCH',
                        data: {
                            _token: '{{ csrf_token() }}'
                        },
                        success: function () {
                            $('#usersTable').DataTable().ajax.reload(null, false);
                        },
                        error: function (xhr) {
                            alertError(xhr.responseJSON?.message ?? 'Action failed');
                        }
                    });
                });
            });
        </script>

        {{-- SAME TAILWIND OVERRIDES AS OTHER TABLES --}}
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