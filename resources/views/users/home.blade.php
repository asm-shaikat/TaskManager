@extends('welcome')

@section('content')
<div class="max-w-2xl mx-auto mt-10 p-6 bg-white rounded-md shadow-md">
    @if (auth()->user()->hasRole('administrator'))
    <h2 class="text-2xl font-semibold mb-6">User List</h2>
    @if (session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
    @endif
    <table class="w-full border">
        <thead>
            <tr>
                <th class="border p-2">Name</th>
                <th class="border p-2">Email</th>
                <th class="border p-2">Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($users as $user)
            <tr>
                <td class="border p-2">{{ $user->name }}</td>
                <td class="border p-2">{{ $user->email }}</td>
                <td class="border p-2">
                    <a href="{{ route('home.edit', $user->id) }}" class="text-blue-500 hover:underline">Edit</a>
                    <form action="{{ route('home.destroy', $user) }}" method="post" class="inline" onsubmit="return confirm('Are you really sure?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-red-500 hover:underline ml-2">Delete</button>
                    </form>
                </td>
            </tr>
            @empty
            <tr>
                <td class="border p-2" colspan="3">No users found.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
    @else
    <h2 class="text-2xl font-semibold mb-6">Task List</h2>
    @php
    $tasks = auth()->user()->tasks()->orderBy('priority')->orderBy('category')->orderBy('due_date')->get();
    $highPriorityTasksCount = $tasks->where('priority', 'high')->count();
    $dueTasksCount = $tasks->where('due_date', '<', now())->count();
        @endphp
        <p class="p-2">
            You have {{$highPriorityTasksCount}} Hight Priority tasks
        </p>
        <p class="p-2">Due date task {{ $dueTasksCount }}</p>
        @if ($tasks->count() > 0)
        <table class="w-full border">
            <thead>
                <tr>
                    <th class="border p-2">Title</th>
                    <th class="border p-2">Priority</th>
                    <th class="border p-2">Category</th>
                    <th class="border p-2">Due Date</th>
                </tr>
            </thead>
            <tbody>
                @foreach($tasks as $task)
                <tr>
                    <td class="border p-2">{{ $task->title }}</td>
                    <td class="border p-2">{{ $task->priority }}</td>
                    <td class="border p-2">{{ $task->category }}</td>
                    <td class="border p-2 {{ $task->due_date && \Carbon\Carbon::parse($task->due_date)->isPast() ? 'text-red-500' : '' }}">
                        {{ $task->due_date ? \Carbon\Carbon::parse($task->due_date)->format('Y-m-d') : 'Not set' }}
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @else
        <p>No tasks found.</p>
        @endif
        @endif
</div>
@endsection