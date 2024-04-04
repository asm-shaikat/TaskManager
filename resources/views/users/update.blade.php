@extends('welcome')
@section('title','Update User')
@section('content')
<div class="max-w-2xl mx-auto mt-10 p-6 bg-white rounded-md shadow-md">
    <h2 class="text-2xl font-semibold mb-6">Edit User Info</h2>

    @if ($errors->any())
    <div class="bg-red-100 text-red-800 p-3 mb-6 rounded">
        <ul>
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <form action="{{ route('users.update', $user->id) }}" method="post" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="mb-4">
            <label for="name" class="block text-sm font-medium text-gray-600">Name</label>
            <input type="text" name="name" id="name" class="mt-1 p-2 w-full border rounded-md" value="{{ old('name', $user->name) }}" required>
        </div>

        <div class="mb-4">
            <label for="email" class="block text-sm font-medium text-gray-600">Email</label>
            <input type="email" name="email" id="email" class="mt-1 p-2 w-full border rounded-md" value="{{ old('email', $user->email) }}" required>
        </div>

        <div class="mb-4">
            <label for="role" class="block text-sm font-medium text-gray-600">Role</label>
            <select name="role" id="role" class="mt-1 p-2 w-full border rounded-md" required>
                <option value="" disabled>Select a role</option>
                @foreach($roles as $role)
                    <option value="{{ $role->id }}" @if ($user->hasRole($role->name)) selected @endif>{{ $role->name }}</option>
                @endforeach
            </select>
        </div>

        <button class="btn btn-success w-full text-white">Update Info</button>
    </form>
</div>
@endsection

@section('script')
<script src="https://cdn.jsdelivr.net/npm/choices.js/public/assets/scripts/choices.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        var selectElement = document.getElementById('role');
        var choices = new Choices(selectElement, {
            itemSelectText: '', // Remove item selection text
        });
    });
</script>
@endsection
