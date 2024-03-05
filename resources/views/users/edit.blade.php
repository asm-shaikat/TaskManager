@extends('welcome')
@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-10 flex flex-col items-center">
    <div class="mb-8">
        <h2 class="text-3xl font-bold text-gray-800">Edit User</h2>
    </div>
    <form action="{{ route('home.update',$user->id) }}" method="post" class="max-w-xl bg-white p-6 rounded-md shadow-md">
        @csrf
        @method('PUT')
        <div class="mb-4">
            <label for="name" class="block text-sm font-medium text-gray-700">Name</label>
            <input type="text" id="name" name="name" value="{{ $user->name }}" class="mt-1 p-2 border rounded-md w-full focus:outline-none focus:ring focus:border-blue-300" required>
        </div>

        <div class="mb-4">
            <label for="name" class="block text-sm font-medium text-gray-700">Email</label>
            <input type="text" id="email" name="email" value="{{ $user->email }}" class="mt-1 p-2 border rounded-md w-full focus:outline-none focus:ring focus:border-blue-300" required>
        </div>
        

        <div class="flex justify-end">
            <button class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:ring focus:border-blue-300">Update</button>
        </div>
    </form>
</div>
@endsection