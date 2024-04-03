@extends('welcome')
@section('title','Create User')
@section('content')
<div class="max-w-2xl mx-auto mt-10 p-6 bg-white rounded-md shadow-md">
    <h2 class="text-2xl font-semibold mb-6">Add User</h2>

    @if ($errors->any())
    <div class="bg-red-100 text-red-800 p-3 mb-6 rounded">
        <ul>
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <form action="{{ route('users.store') }}" method="post" enctype="multipart/form-data">
        @csrf

        <div class="mb-4">
            <label for="name" class="block text-sm font-medium text-gray-600">Name <span class="text-red-600"> *</span></label>
            <input type="text" name="name" id="name" class="mt-1 p-2 w-full border rounded-md" value="{{ old('name') }}" required>
        </div>

        <div class="mb-4">
            <label for="email" class="block text-sm font-medium text-gray-600">Email<span class="text-red-600"> *</span></label>
            <input type="email" name="email" id="email" class="mt-1 p-2 w-full border rounded-md" value="{{ old('email') }}" required>
        </div>

        <div class="mb-4">
            <label for="password" class="block text-sm font-medium text-gray-600">Password<span class="text-red-600"> *</span></label>
            <input type="password" name="password" id="password" class="mt-1 p-2 w-full border rounded-md" value="{{ old('password') }}" required>
        </div>

        <div class="mb-4">
            <label for="password_confirmation" class="block text-sm font-medium text-gray-600">Confirm Password<span class="text-red-600"> *</span></label>
            <input type="password" name="password_confirmation" id="password_confirmation" class="mt-1 p-2 w-full border rounded-md" value="{{ old('password_confirmation') }}" required>
        </div>

        <div class="grid grid-cols-4 gap-4 mt-10 mb-10">
            @foreach($roles as $role)
            <div class="mb-4">
                <input type="radio" name="role" value="{{ $role->id }}" id="role{{ $role->id }}" class="radio" />
                <label for="role{{ $role->id }}" class="block text-sm font-medium text-gray-600 ml-2">{{ $role->name }}</label>
            </div>
            @endforeach
        </div>

        <button class="btn btn-success w-full text-white">ADD</button>
    </form>

</div>

@endsection