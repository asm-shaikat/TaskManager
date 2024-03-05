@extends('welcome')
@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-10 flex flex-col items-center">
    <div class="mb-8">
        <h2 class="text-3xl font-bold text-gray-800">Edit Permission</h2>
    </div>
    <form action="{{ route('permission.update', ['permission' => $permission->id]) }}" method="post" class="max-w-md bg-white p-6 rounded-md shadow-md">
        @csrf
        @method('PUT')
        <div class="mb-4">
            <label for="edit_permission_name" class="block text-sm font-medium text-gray-700">Permission Name</label>
            <input type="text" id="edit_permission_name" name="edit_permission_name" value="{{ $permission->name }}" class="mt-1 p-2 border rounded-md w-full focus:outline-none focus:ring focus:border-blue-300" required>
        </div>

        <div class="flex justify-end">
            <button class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:ring focus:border-blue-300">Update</button>
        </div>
    </form>

    <div class="mt-8">
        <div class="mb-4">
            <h2 class="text-xl font-bold text-gray-800">Role</h2>
        </div>
        @if($permission->roles)
        <div class="flex gap-4">
            @foreach($permission->roles as $permission_role)
            <form action="{{ route('administrator.removePermissions.role', [$permission_role->id, $permission->id]) }}" onsubmit="return confirm('Are you sure?')" method="post">
                @method('DELETE')
                @csrf
                <button class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:ring focus:border-blue-300">{{ $permission_role->name }}</button>
            </form>
            @endforeach
        </div>
        @endif
    </div>
</div>
@endsection