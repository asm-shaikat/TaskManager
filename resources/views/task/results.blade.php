<tbody>
    @foreach($tasks as $task)
    <tr>
        <td class="border p-2">
            <a href="{{ route('task.show', $task) }}" class="text-blue-500 hover:underline">{{ $task->title }}</a>
        </td>
        <td class="border p-2">{{ $task->description }}</td>
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