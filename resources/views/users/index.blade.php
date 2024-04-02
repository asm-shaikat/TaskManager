@extends('welcome')
@section('title','User List')
@section('content')
<div class="max-w-full mx-auto mt-10 p-6 bg-white rounded-md shadow-md">
    <div class="flex justify-between">
        <div>
            <h2 class="text-2xl font-semibold mb-6">Users List</h2>
        </div>
        @can('create user')
        <div>
            <a href="{{ route('users.create') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">ADD User</a>
        </div>
        @endcan
    </div>
    <table class="w-full border" id="yajraUserTable">
        <thead>
            <tr>
                <th class="border p-2">Name</th>
                <th class="border p-2">Email</th>
                <th class="border p-2">Role</th>
                <th class="border p-2">Actions</th>
            </tr>
        </thead>
        <tbody>
           
        </tbody>
    </table>
</div>
@endsection
@section('script')
<script>
    $('#yajraUserTable').DataTable( {
    serverSide: true,
    ajax: {
        url: '/users',
        type: 'GET'
    },
    columns:[
        {
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
        { data: 'actions', name: 'actions', orderable: false, searchable: false },
    ]
} );
</script>
@endsection