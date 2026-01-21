<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Users
        </h2>
    </x-slot>

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

        <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
        <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
        <script src="https://cdn.datatables.net/1.13.7/js/dataTables.tailwindcss.min.js"></script>

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

                if (!confirm('Are you sure you want to change user status?')) {
                    return;
                }

                let button = $(this);
                let userId = button.data('id');

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
                        alert(xhr.responseJSON?.message ?? 'Action failed');
                    }
                });
            });
        </script>

    @endpush
</x-app-layout>