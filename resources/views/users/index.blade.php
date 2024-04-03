@extends('welcome')
@section('title','User List')
@section('content')
<div class="max-w-full mx-auto mt-2 p-6 bg-white rounded-md shadow-md">
    <div class="flex justify-between">
        <div>
            <h2 class="text-2xl font-semibold mb-6">Users List</h2>
        </div>
        @can('create user')
        <div>
            <a href="{{ route('users.create') }}">
                <button class="btn h-10 w-44 bg-blue-500 hover:bg-blue-700 text-white">
                    <img src="{{ asset('assets/images/svg/plus-solid.svg') }}" style="filter: invert(100%);" class="w-6" alt="">
                </button>
            </a>
        </div>
        @endcan
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
    $('#yajraUserTable').DataTable({
        serverSide: true,
        ajax: {
            url: '/users',
            type: 'GET'
        },
        columns: [{
                data: 'name',
                name: 'name',
            },
            {
                data: 'email',
                name: 'email',
            },
            {
                data: 'role',
                name: 'role',
            },
            {
                data: 'actions',
                name: 'actions',
                orderable: false,
                searchable: false
            },
        ]
    });
</script>
@endsection