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
    <table class="w-full border">
        <thead>
            <tr>
                <th class="border p-2">Name</th>
                <th class="border p-2">Email</th>
                <th class="border p-2">Role</th>
                <th class="border p-2">Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($users as $user)
            <tr>
                <td class="border p-2">{{ $user->name }}</td>
                <td class="border p-2">{{ $user->email }}</td>
                <td class="border p-2">
                    @foreach($user->roles as $role)
                    <li>{{ $role->name }}</li>
                    @endforeach
                </td>
                <td class="border p-2 text-center">
                    <div class="flex gap-2">
                        <div class="p-2">
                            <a href="{{ route('users.edit', $user->id) }}" class="text-blue-500 hover:underline">
                                <img src="{{ asset('assets/images/svg/pencil-solid.svg') }}" class="w-4" alt="user-svg">
                            </a>
                        </div>
                        <div class="p-2">
                        <form action="{{ route('users.destroy', $user->id) }}" method="post" class="inline" onsubmit="return confirm('Are you really sure?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-500 hover:underline ml-2">Delete</button>
                        </form>
                        </div>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td class="border p-2" colspan="3">No users found.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection