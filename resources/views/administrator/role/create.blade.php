@extends('welcome')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-5">
    <div class="flex justify-between items-center mb-4">
        <h2 class="text-2xl font-bold">Role Management</h2>
        <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded" onclick="my_modal_2.showModal()">Create Role</button>
    </div>

    <form action="{{ route('role.store') }}" method="post" class="py-4" enctype="multipart/form-data">
    @csrf
    <div class="mb-4">
        <label for="roleName" class="block text-sm font-medium text-gray-600">Role Name</label>
        <input type="text" id="roleName" name="roleName" class="mt-1 p-2 border rounded-md w-full" required>
    </div>

    <div class="grid grid-cols-4 gap-4 mt-10 mb-10">
        @foreach($permissions as $permission)
        <div class="mb-4">
            <input type="checkbox" name="permissions[]" value="{{ $permission->id }}" id="permission{{ $permission->id }}" class="checkbox" />
            <label for="permission{{ $permission->id }}" class="block text-sm font-medium text-gray-600 ml-2">{{ $permission->name }}</label>
        </div>
        @endforeach
    </div>

    <div class="flex justify-between items-center">
        <!-- Submit button -->
        <button class=" bg-green-500 hover:bg-green-900 text-white font-bold py-2 px-4 rounded mr-2">Submit</button>
    </div>
</form>


</div>

@endsection