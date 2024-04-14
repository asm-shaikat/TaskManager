@extends('welcome')
@section('title','User List')
@section('content')

<!-- Main section -->
<div class="max-w-full mx-auto mt-2 p-6 bg-white rounded-md shadow-md" id="OrginalData">
    <div class="flex justify-between items-center mb-4">
        <div>
            <h2 class="text-2xl font-semibold">Users List</h2>
        </div>
        @can('create user')
        <div>
            <a href="{{ route('users.create') }}">
                <button class="btn h-10 w-44 bg-blue-500 hover:bg-blue-700 text-white">
                    <p class="p-4">Create</p>
                    <img src="{{ asset('assets/images/svg/plus-solid.svg') }}" style="filter: invert(100%);" class="w-4" alt="">
                </button>
            </a>
        </div>
        @endcan
    </div>
    <!-- List and Deleted buttons -->
    <div class="mb-6">
        <button class="btn mr-4 toggle-btn text-white" data-target="OrginalData" style="background-color: #0096FF">List</button>
        <button class="btn toggle-btn" data-target="DeleteData">Deleted</button>
    </div>

    <div class="flex gap-4">
        <div class="w-1/4 mb-4">
            <label for="role_filter" class="block text-sm font-medium text-gray-700 mr-4 p-1">Filter by Role:</label>
            <select id="role_filter" name="role_filter" class="h-12 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                <option value="">All Roles</option>
                @foreach($user_roles as $role)
                <option value="{{ $role->name }}">{{ $role->name }}</option>
                @endforeach
            </select>
        </div>

        <!-- Custom searching -->
        <div class="w-1/4 mr-4">
            <label for="custom_search">Search:</label>
            <input type="text" id="custom_search" class="mt-1 h-12 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
        </div>
    </div>


    <table class="min-w-full divide-y divide-gray-200" id="yajraUserTable">
        <thead class="bg-gray-100">
            <tr>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Role</th>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
            </tr>
        <tbody>
        </tbody>
        </tbody>
    </table>
</div>
<!-- End Main section -->

<!-- Toggle deleted section -->
<div class="max-w-full mx-auto mt-2 p-6 bg-white rounded-md shadow-md hidden" id="DeleteData">
    <div class="flex justify-between items-center mb-4">
        <div>
            <h2 class="text-2xl font-semibold">Users List</h2>
        </div>
        @can('create user')
        <div>
            <a href="{{ route('users.create') }}">
                <button class="btn h-10 w-44 bg-blue-500 hover:bg-blue-700 text-white">
                    <p class="p-4">Create</p>
                    <img src="{{ asset('assets/images/svg/plus-solid.svg') }}" style="filter: invert(100%);" class="w-6" alt="">
                </button>
            </a>
        </div>
        @endcan
    </div>
    <!-- List and Deleted buttons -->
    <div class="mb-6">
        <button class="btn mr-4 toggle-btn" data-target="OrginalData">List</button>
        <button class="btn toggle-btn text-white" data-target="DeleteData" style="background-color: #0096FF">Deleted</button>
    </div>
    <table class="min-w-full divide-y divide-gray-200" id="deletedTable">
        <thead class="bg-gray-100">
            <tr>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Role</th>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
            </tr>
        <tbody>
        </tbody>
        </tbody>
    </table>
</div>
<!-- End of toggle delete section -->
@endsection

@section('script')
<script>
    $(document).ready(function() {
        var table = $('#yajraUserTable').DataTable({
            serverSide: true,
            ajax: {
                url: '/users',
                type: 'GET',
                data: function(data) {
                    data.role_filter = $('#role_filter').val();
                }
            },
            columns: [{
                    data: 'name',
                    name: 'name'
                },
                {
                    data: 'email',
                    name: 'email'
                },
                {
                    data: 'role',
                    name: 'role'
                },
                {
                    data: 'actions',
                    name: 'actions',
                    orderable: false,
                    searchable: false
                }
            ],
            lengthChange: false,

        });
        var deletedTable;
        // Toggle functionality
        $('.toggle-btn').click(function() {
            var target = $(this).data('target');
            $('#' + target).show();
            $('.max-w-full').not('#' + target).hide();
            $(this).addClass('active').siblings().removeClass('active');

            if (target === 'OrginalData') {
                table.ajax.url('/users').load(); // Reload the original table
            } else {
                if (!deletedTable) {
                    // Initialize the deletedTable DataTable if it hasn't been initialized yet
                    deletedTable = $('#deletedTable').DataTable({
                        serverSide: true,
                        ajax: {
                            url: '/users',
                            type: 'GET',
                            data: {
                                show_deleted: true
                            }
                        },
                        columns: [{
                                data: 'name',
                                name: 'name'
                            },
                            {
                                data: 'email',
                                name: 'email'
                            },
                            {
                                data: 'role',
                                name: 'role'
                            },
                            {
                                data: 'actions',
                                name: 'actions',
                                orderable: false,
                                searchable: false
                            } // Removed the extra comma here
                        ],
                        lengthMenu: [
                            [10, 25, 50, -1],
                            [10, 25, 50, "All"]
                        ]
                    });
                } else {
                    // If the deletedTable DataTable is already initialized, just reload it
                    deletedTable.ajax.url('/users?show_deleted=true').load();
                }
            }
        });

        // Event listener for role filter
        $('#role_filter').on('change', function() {
            table.ajax.reload();
        });

        // custom search function
        $('#custom_search').on('keyup', function() {
            var searchTerm = $(this).val();
            table.search(searchTerm).draw(); // Apply the search term and redraw the DataTable
        });

        // Delete functionality
        $(document).on('click', '.delete-btn', function(e) {
            e.preventDefault();
            var form = $(this).closest('form');
            var section = $(this).closest('.max-w-full').attr('id');

            var deleteType = 'soft_delete'; // Default delete type (soft delete)

            if (section === 'DeleteData') {
                // For hard delete
                deleteType = 'hard_delete';
            }

            // Set the value of the hidden input field
            $('#delete_type').val(deleteType);

            var confirmationText = 'Are you sure you want to delete this user?';
            if (deleteType === 'hard_delete') {
                confirmationText += ' This action will permanently delete the user.';
            }

            Swal.fire({
                title: 'Confirmation',
                text: confirmationText,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Yes, delete it'
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        });
    });
</script>
@endsection