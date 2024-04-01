@extends('welcome')

@section('content')
<div class="max-w-full mx-auto mt-10 p-6 bg-white rounded-md shadow-md">
    <div class="flex justify-between">
        <div>
            <h2 class="text-2xl font-semibold mb-6">My Tasks</h2>
        </div>
        <div>
            <a href="{{ route('task.create') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Create Task</a>
        </div>
    </div>
    
    <div class="mb-4">
        <p class="text-gray-600">You have {{ $tasksCount }} tasks assigned.</p>
    </div>

    <div class="mb-4">
        @if($tasksCount > 0)
        <table class="w-full p-4 border display" id="dtExample">
            <thead>
                <tr>
                    <th class="p-2">Title</th>
                    <th class="p-2">Description</th>
                    <th class="p-2">Priority</th>
                    <th class="p-2">Due Date</th>
                    <th class="p-2">Actions</th> <!-- New column for actions -->
                </tr>
            </thead>
            <tbody>
                @foreach($tasks as $task)
                <tr>
                    <td class="border p-2">
                        <a href="{{ route('task.show', $task) }}" class="text-blue-500 hover:underline">{{ $task->title }}</a>
                    </td>
                    <td class="border p-2">{{ $task->description }}</td>
                    <td class="border p-2">{{ $task->priority }}</td>
                    <td class="border p-2">{{ $task->due_date ? \Carbon\Carbon::parse($task->due_date)->format('Y-m-d') : 'Not set' }}</td>
                    <td class="border p-2">
                        <a href="{{ route('task.edit', $task->id) }}" class="text-green-500 hover:underline">Edit</a>
                        <form action="{{ route('task.destroy', $task) }}" method="post" class="inline" onsubmit="return confirm('Are you really sure?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-500 hover:underline ml-2">Delete</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @else
        <p class="text-gray-600">No tasks assigned to you.</p>
        @endif
    </div>
</div>

@endsection
