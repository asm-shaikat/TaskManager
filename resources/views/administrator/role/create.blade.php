@extends('welcome')
@section('title','Create Role')
@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-5">
    <div class="flex justify-between items-center mb-4">
        <h2 class="text-2xl font-bold">Role Management</h2>
    </div>

    <form action="{{ route('role.store') }}" method="post" class="py-4" enctype="multipart/form-data">
    @csrf
    <div class="mb-4">
        <label for="roleName" class="block text-sm font-medium text-gray-600">Role Name <span class="text-red-600">*</span></label>
        <input type="text" id="roleName" name="roleName" class="mt-1 p-2 border rounded-md w-full" required>
    </div>
    <p>Give permission to the Role</p>
    <div class="grid grid-cols-1 gap-4 mt-10 mb-10">
        <select name="permissions[]" id="permissions" multiple="multiple" class="js-example-basic-multiple w-full">
            @foreach($permissions as $permission)
            <option value="{{ $permission->id }}">{{ $permission->name }}</option>
            @endforeach
        </select>
    </div>

    <div class="flex justify-between items-center">
        <!-- Submit button -->
        <button class=" bg-green-500 hover:bg-green-900 text-white font-bold py-2 px-4 rounded mr-2">Submit</button>
    </div>
</form>


</div>

@endsection

@section('style')
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" />
<style>
    .select2-container .select2-selection--multiple {
        width: 100% !important;
    }
    .select2-container--default .select2-selection--multiple {
        border: 1px solid #a0aec0;
        border-radius: 0.375rem;
        padding: 0.5rem;
        height: auto !important;
    }
    .select2-container--default .select2-selection--multiple .select2-selection__choice {
        background-color: #edf2f7;
        color: #2d3748;
        border: none;
    }
</style>
@endsection

@section('script')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>
<script>
    // Select2
    $(document).ready(function() {
        $('.js-example-basic-multiple').select2();
    });
    // End of Select2
</script>
@endsection
