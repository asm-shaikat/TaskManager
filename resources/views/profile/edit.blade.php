@extends('welcome')
@section('title', 'Profile')
@section('content')
<div class="flex items-center justify-center h-full">
    <div class="max-w-md w-full bg-white rounded-md shadow-md p-6 space-y-4">
        <h2 class="text-2xl mb-4">Update Profile</h2>
        
        <!-- Form for updating name and email -->
        <form action="{{ route('profile.updateNameEmail', auth()->user()->id) }}" method="POST" class="space-y-4">
            @csrf
            @method('PUT')
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

        <!-- Form for updating password -->
        <form action="{{ route('profile.updatePassword', auth()->user()->id) }}" method="POST" class="space-y-4">
            @csrf
            @method('PUT')
            <!-- Old Password -->
            <div>
                <label for="old_password" class="block font-medium">Old Password</label>
                <input type="password" id="old_password" name="old_password" class="input input-bordered w-full">
                @error('old_password')
                    <p class="text-red-500 mt-1 text-sm">{{ $message }}</p>
                @enderror
            </div>

            <!-- New Password -->
            <div>
                <label for="password" class="block font-medium">New Password</label>
                <input type="password" id="password" name="password" class="input input-bordered w-full">
                @error('password')
                    <p class="text-red-500 mt-1 text-sm">{{ $message }}</p>
                @enderror
            </div>

            <!-- Confirm New Password -->
            <div>
                <label for="password_confirmation" class="block font-medium">Confirm New Password</label>
                <input type="password" id="password_confirmation" name="password_confirmation" class="input input-bordered w-full">
                @error('password_confirmation')
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
    // You can add any client-side validation or functionality here
</script>
@endsection
