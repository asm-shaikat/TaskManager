@extends('welcome')

@section('content')
<div class="max-w-2xl mx-auto mt-10 p-6 bg-white rounded-md shadow-md">
    <h2 class="text-2xl font-semibold mb-6">Task Details</h2>

    <div class="mb-4">
        <h3 class="text-xl font-semibold">{{ $task->title }}</h3>
    </div>

    <div class="mb-4">
        <p class="text-gray-800">{{ $task->description }}</p>
    </div>

    <div class="mb-4">
        <p class="text-gray-600">Priority: {{ $task->priority }}</p>
        <p class="text-gray-600">Category: {{ $task->category }}</p>
        <p class="text-gray-600">Due Date: {{ $task->due_date ? \Carbon\Carbon::parse($task->due_date)->format('Y-m-d') : 'Not set' }}</p>
    </div>

    <div>
        <a href="{{ route('task.index') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Back to My Tasks</a>
    </div>
</div>
@endsection
