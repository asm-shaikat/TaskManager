@extends('welcome')
@section('title','Role Update')
@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-5">
    <div class="flex justify-between items-center mb-4">
        <h2 class="text-2xl font-bold">Role Management</h2>
        <a href="{{ route('role.create') }}">
            <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Create Role</button>
        </a>
    </div>

    <form action="{{ route('role.update', $role->id) }}" method="post">
        @csrf
        @method('PUT')

        <div class="mb-4">
            <label for="roleName" class="block text-sm font-medium text-gray-600">Role Name</label>
            <input type="text" id="roleName" name="roleName" class="mt-1 p-2 border rounded-md w-full" value="{{ $role->name }}" required>
        </div>
        <p>Assign Permission</p>
        <div class="grid grid-cols-4 gap-4 mt-10 mb-10">
            @foreach($permissions as $permission)
                <div class="mb-4">
                    <input type="checkbox" name="permissions[]" value="{{ $permission->id }}" id="permission{{ $permission->id }}" class="checkbox" @if ($role->hasPermissionTo($permission)) checked @endif />
                    <label for="permission{{ $permission->id }}" class="block text-sm font-medium text-gray-600 ml-2">{{ $permission->name }}</label>
                </div>
            @endforeach
        </div>

        <div class="flex justify-between items-center">
            <!-- Submit button -->
            <button type="submit" class="btn text-white" style="background-color: #0096FF;">Update</button>
        </div>
    </form>

</div>

@endsection