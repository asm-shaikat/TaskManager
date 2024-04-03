@extends('welcome')
@section('title','Task View')
@section('content')
<div class="flex w-full">
    @section('title','Create Task')
    <div class="w-3/4 p-6 m-auto ml-10">
        <h2 class="text-2xl font-semibold mb-6">Task Details</h2>

        <div class="mb-4">
            <h3 class="text-xl font-semibold">{{ $task->title }}</h3>
        </div>

        <div class="mb-4">
            <small class="text-gray-800">{{ $task->description }}</small>
        </div>

        <div class="mb-4">
            <p class="text-gray-600">Priority: {{ $task->priority }}</p>
            <p class="text-gray-600">Category: {{ $task->category }}</p>
            <p class="text-gray-600">Due Date: {{ $task->due_date ? \Carbon\Carbon::parse($task->due_date)->format('Y-m-d') : 'Not set' }}</p>
        </div>

        <form action="{{ route('comments.store') }}" method="post" enctype="multipart/form-data">
            @csrf
            <input hidden type="text" name="task_id" value="{{ $task->id }}">
        
            <div class="mb-4">
                <img id="attachment-preview" src="{{ asset('assets/images/default-image.webp') }}" class="h-60 w-full" alt="">
            </div>

            <div class="mb-4">
                <label for="content" class="block text-sm font-medium text-gray-600">Add Comment</label>
                <textarea name="content" id="content" placeholder="type your comment" class="mt-1 p-2 w-full border rounded-md" required></textarea>
            </div>

            <button class="btn btn-success w-full text-white">ADD Comment</button>
        </form>
    </div>
    <div>

    </div>
    <div class="w-2/4 mx-auto p-6">
        <h3 class="text-xl font-semibold">Comments</h3>
        <div id="comments-container" class="mb-4" style="max-height: 600px; overflow-y: auto;">
            <div class="sticky top-0 bg-white z-10">
                @foreach($comments as $comment)
                <div class="chat chat-start">
                    <div class="chat-image avatar">
                        <div class="w-10 rounded-full">
                            <img alt="Tailwind CSS chat bubble component" src="{{ asset('assets/images/avater.png') }}" />
                        </div>
                    </div>
                    <div class="chat-header">
                        {{ $comment->user->name }}
                        <time class="text-xs opacity-50">{{ $comment->created_at->format('H:i') }}</time>
                    </div>
                    <div class="chat-bubble bg-blue-400 text-white">{{ $comment->content }}</div>
                </div>
                @endforeach
            </div>
        </div>
    </div>

</div>
@endsection
@section('script')
<script>
    // file preview
    $(document).ready(function() {
        $('#attachment').change(function(e) {
            var file = e.target.files[0];
            var reader = new FileReader();
            reader.onload = function(e) {
                $('#attachment-preview').attr('src', e.target.result).removeClass('hidden');
            };
            reader.readAsDataURL(file);
        });

        function scrollToBottom() {
            var container = document.getElementById('comments-container');
            container.scrollTop = container.scrollHeight;
        }
        scrollToBottom();
    });
</script>
@endsection
