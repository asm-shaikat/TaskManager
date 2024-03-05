@extends('welcome')

@section('content')
<div class="flex">
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

        <form action="{{ route('comments.store') }}" method="post" enctype="multipart/form-data">
            @csrf
            <div class="mb-4">
                <label for="content" class="block text-sm font-medium text-gray-600">Add Comment</label>
                <textarea name="content" id="content" placeholder="type your comment" class="mt-1 p-2 w-full border rounded-md" required></textarea>
            </div>

            <input hidden type="text" name="task_id" value="{{ $task->id }}">

            <div class="mb-4">
                <label for="attachment" class="block text-sm font-medium text-gray-600">Attachment</label>
                <input type="file" name="attachment" id="attachment" class="mt-1 p-2 w-full border rounded-md">
            </div>
            <button class="btn btn-success w-full text-white">ADD Comment</button>
        </form>
    </div>
    <div class="max-w-2xl mx-auto mt-10 p-6 bg-white rounded-md shadow-md">
        <div class="mb-4">
            <div class="mb-4">
                <h3 class="text-xl font-semibold">Comments</h3>
                @foreach($comments as $comment)
                <p>{{ $comment->content }}</p>
                <a href="{{ asset($comment->attachments) }}" class="text-red-600" download>Download PDF</a>
                @endforeach
            </div>

        </div>
    </div>
</div>
@section('script')

@endsection
@endsection