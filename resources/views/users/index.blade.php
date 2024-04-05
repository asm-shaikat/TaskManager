@extends('welcome')
@section('title','User List')
@section('content')
<div class="max-w-full mx-auto mt-2 p-6 bg-white rounded-md shadow-md">
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
    <div class="flex mb-4">
        <label for="role_filter" class="block text-sm font-medium text-gray-700 mr-4">Filter by Role:</label>
        <select id="role_filter" name="role_filter" class="h-12 block w-1/4 py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
            <option value="">All Roles</option>
            @foreach($user_roles as $role)
            <option value="{{ $role->name }}">{{ $role->name }}</option>
            @endforeach
        </select>
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
@endsection

@section('script')
<script>
    $(document).ready(function() {
        var table = $('#yajraUserTable').DataTable({
            serverSide: true,
            ajax: {
                url: '/users',
                type: 'GET'
            },
            columns: [
                { data: 'name', name: 'name' },
                { data: 'email', name: 'email' },
                { data: 'role', name: 'role' },
                { data: 'actions', name: 'actions', orderable: false, searchable: false }
            ]
        });

        // Custom filtering for Role column
        $('#role_filter').change(function() {
            var role = $(this).val();
            var x = table.column(2).search(role).draw();
            console.log(x);
        });
        // Delete button functionality
        $(document).on('click', '.delete-btn', function(e) {
        e.preventDefault();
        var form = $(this).closest('form');
        var name = $(this).data('name');

        Swal.fire({
            title: 'Are you sure?',
            text: 'You want to delete ' + name + '?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
                form.submit();
            }
        });
    });
    });
</script>
@endsection
