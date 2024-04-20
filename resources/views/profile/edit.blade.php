@extends('welcome')
@section('title', 'Profile')
@section('content')
<div class="flex items-center justify-center h-full">
    <div class="max-w-md w-full bg-white rounded-md shadow-md p-6 space-y-4">
        <h2 class="text-2xl mb-4">Update Profile</h2>

        <!-- Form for updating name and email -->
        <form action="{{ route('profile.updateNameEmail', auth()->user()->id) }}" method="POST" enctype="multipart/form-data" class="space-y-4">
            @csrf
            @method('PUT')
            <div class="w-full flex justify-center">
                <img id="avatarPreview" class="mt-2 border border-slate-700 rounded-full shadow-xl" src="{{ auth()->user()->avatar ? asset('storage/'.auth()->user()->avatar) : asset('assets/images/avatar.png') }}" alt="Avatar" style="max-width: 100px;">
            </div>

            <div>
                <label for="avatar" class="block font-medium">Avatar</label>
                <input type="file" id="avatar" name="avatar" accept="image/*" class="file-input file-input-bordered file-input-ghost w-full" />
            </div>

            <!-- Name -->
            <div>
                <label for="name" class="block font-medium">Name</label>
                <input type="text" id="name" name="name" class="input input-bordered w-full" value="{{ old('name', auth()->user()->name) }}">
                @error('name')
                <p class="text-red-500 mt-1 text-sm">{{ $message }}</p>
                @enderror
            </div>

            <!-- Email -->
            <div>
                <label for="email" class="block font-medium">Email</label>
                <input type="email" id="email" name="email" class="input input-bordered w-full" value="{{ old('email', auth()->user()->email) }}">
                @error('email')
                <p class="text-red-500 mt-1 text-sm">{{ $message }}</p>
                @enderror
            </div>

            <!-- Submit Button -->
            <button type="submit" class="btn w-full text-white" style="background-color: #0096FF;">Update</button>
        </form>
    </div>
</div>
@endsection

@section('script')
<script>
    document.getElementById('avatar').addEventListener('change', function(event) {
        var file = event.target.files[0];
        var reader = new FileReader();

        reader.onload = function(e) {
            document.getElementById('avatarPreview').src = e.target.result;
        }

        reader.readAsDataURL(file);
    });
</script>
@endsection