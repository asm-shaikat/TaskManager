@extends('welcome')

@section('content')
<div class="max-w-2xl mx-auto mt-10 p-6 bg-white rounded-md shadow-md">
    <h2 class="text-2xl font-semibold mb-6">Add Task</h2>

    @if ($errors->any())
    <div class="bg-red-100 text-red-800 p-3 mb-6 rounded">
        <ul>
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <form action="{{ route('task.store') }}" method="post" enctype="multipart/form-data">
        @csrf

        <div class="mb-4">
            <label for="title" class="block text-sm font-medium text-gray-600">Title</label>
            <input type="text" name="title" id="title" class="mt-1 p-2 w-full border rounded-md" value="{{ old('title') }}" required>
        </div>

        @if (auth()->user()->hasRole('administrator'))
        @php
        $nonAdminUsers = $users->reject(function ($user) {
        return $user->hasRole('administrator');
        });
        @endphp

        <div class="mb-4">
            <label for="user_id" class="block text-sm font-medium text-gray-600">Assign User</label>
            <select name="user_id" id="user_id" class="mt-1 p-2 w-full border rounded-md" required>
                <option value="{{ auth()->user()->id }}" selected>Assign to myself ({{ auth()->user()->name }})</option>
                @foreach ($nonAdminUsers as $user)
                <option value="{{ $user->id }}">{{ $user->name }}</option>
                @endforeach
            </select>
        </div>
        @else
        <input type="hidden" name="user_id" value="{{ auth()->user()->id }}">
        @endif

        <div class="mb-4">
            <label for="description" class="block text-sm font-medium text-gray-600">Description</label>
            <textarea name="description" id="description" class="mt-1 p-2 w-full border rounded-md">{{ old('description') }}</textarea>
        </div>

        <div class="mb-4">
            <label for="priority" class="block text-sm font-medium text-gray-600">Priority</label>
            <select name="priority" id="priority" class="mt-1 p-2 w-full border rounded-md" required>
                <option value="low">Low</option>
                <option value="medium" selected>Medium</option>
                <option value="high">High</option>
            </select>
        </div>

        <div class="mb-4">
            <label for="category" class="block text-sm font-medium text-gray-600">Category</label>
            <select name="category" id="category" class="mt-1 p-2 w-full border rounded-md" required>
                <option value="work">Work</option>
                <option value="personal">Personal</option>
            </select>
        </div>

        <div class="mb-4 relative">
            <label for="due_date" class="block text-sm font-medium text-gray-600">Due Date</label>
            <div class="flex items-center border rounded-md">
                <input type="text" name="due_date" id="datepicker" class="mt-1 p-2 w-full rounded-md focus:outline-none" placeholder="Select Due Date" value="{{ old('due_date') }}">
                <span id="datepicker-icon" class="absolute right-0 mr-2 cursor-pointer">
                    <i class="fas fa-calendar text-green-500"></i>
                </span>
            </div>
        </div>



        <div class="mb-4">
            <label for="attachment" class="block text-sm font-medium text-gray-600">Attachment</label>
            <input type="file" name="attachment" id="attachment" class="mt-1 p-2 w-full border rounded-md">
        </div>

        <button class="btn btn-success w-full text-white">ADD</button>
    </form>
</div>
@endsection
@section('script')
<script>
    // Datepickr
    var datepickerIcon = document.getElementById('datepicker-icon');
    datepickerIcon.addEventListener('click', function() {
        var datepickerInput = document.getElementById('datepicker');
        datepickerInput.focus();
    });
    flatpickr("#datepicker", {
        dateFormat: "Y-m-d",
    });
    // End Datepickr
</script>
@endsection