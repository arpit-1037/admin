<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Users
        </h2>
    </x-slot>
    <x-slot name="sidebar">
        @include('partials.sidebar')
    </x-slot>
    @push('styles')
    <style>
        /* Search input */
        .dataTables_filter input {
            border: 1px solid #d1d5db;
            border-radius: 0.375rem;
            padding: 6px 10px;
            margin-left: 8px;
        }

        /* Pagination buttons */
        .dataTables_wrapper .dataTables_paginate .paginate_button {
            padding: 4px 10px;
            margin: 0 2px;
            border-radius: 0.375rem;
            border: 1px solid #d1d5db;
            color: #374151 !important;
        }

        /* Active page */
        .dataTables_wrapper .dataTables_paginate .paginate_button.current {
            background: #16a34a !important;
            color: #ffffff !important;
            border-color: #16a34a;
        }

        /* Hover state */
        .dataTables_wrapper .dataTables_paginate .paginate_button:hover {
            background: #dcfce7 !important;
            color: #065f46 !important;
        }
    </style>


    <div class="py-10">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            {{-- Action Bar --}}
            <div class="flex justify-between items-center mb-6">
                <p class="text-gray-600">
                    Manage all registered users
                </p>
            </div>

            {{-- Users Table --}}
            <div class="bg-white shadow-sm sm:rounded-lg">
                <div class="p-6 overflow-x-auto">

                    <table id="usersTable" class="w-full table-auto border-collapse">
                        <thead>
                            <tr class="border-b text-left text-gray-600">
                                <th class="py-3 px-2">#</th>
                                <th class="py-3 px-2">Name</th>
                                <th class="py-3 px-2">Email</th>
                                <th class="py-3 px-2">Status</th>
                                <th class="py-3 px-2">Registered On</th>
                                <th class="py-3 px-2">Actions</th>
                            </tr>
                        </thead>
                        {{-- tbody populated by DataTables --}}
                    </table>

                </div>
            </div>

        </div>
    </div>


    @push('scripts')

        <script>
            $(document).ready(function () {
                $('#usersTable').DataTable({
                    processing: true,
                    serverSide: true,
                    ajax: "{{ route('admin.users.index') }}",
                    pageLength: 10,
                    lengthChange: false,
                    order: [[1, 'asc']],
                    language: {
                        search: '',
                        searchPlaceholder: 'Search users...'
                    },
                    columns: [
                        { data: 'DT_RowIndex', orderable: false, searchable: false },
                        { data: 'name' },
                        { data: 'email' },
                        { data: 'status', orderable: false, searchable: false },
                        { data: 'registered_on' },
                        { data: 'actions', orderable: false, searchable: false }
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

                    if (!result.isConfirmed) {
                        return;
                    }

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
    @endpush


</x-app-layout>