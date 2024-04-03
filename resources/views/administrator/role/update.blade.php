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
        <div class="grid grid-cols-1 gap-4 mt-10 mb-10"> <!-- Modified grid-cols-1 here -->
            <select name="permissions[]" id="permissions" multiple="multiple" class="js-example-basic-multiple w-full">
                @foreach($permissions as $permission)
                    <option value="{{ $permission->id }}" @if($role->hasPermissionTo($permission)) selected @endif>{{ $permission->name }}</option>
                @endforeach
            </select>
        </div>

        <div class="flex justify-between items-center">
            <!-- Submit button -->
            <button type="submit" class="btn btn-success">Update</button>
        </div>
    </form>

</div>

@endsection

@section('style')
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" />
<style>
    .grid-cols-1 {
        grid-template-columns: 1fr;
    }

    .w-full {
        width: 100%;
    }


</style>
@endsection

@section('script')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>
<script>
    // Initialize Select2
    $(document).ready(function() {
        $('.js-example-basic-multiple').select2();
    });
</script>
@endsection
