@extends('welcome')
@section('title','Task View')
@section('content')
<div class="flex w-full">
    <div class="w-3/4 ml-4">
        <div class="mb-4">
            <h3 class="text-xl font-semibold inline-flex">{{ $task->title }}</h3>
            <small class="badge badge-accent inline-block text-center">{{ $task->status }}</small>
        </div>
        <div>
            @foreach ($tags->labels as $label)
            <span class="bg-slate-200 rounded-lg p-1 font-extralight">{{ $label->label }}</span>
            @endforeach
        </div>
        <div class="mb-4">
            <small class="text-gray-800">{{ $task->description }}</small>
        </div>

        <div class="mb-4">
            @if($task->priority === 'low')
            <p class="text-green-600 priority-low">Priority: {{ $task->priority }}</p>
            @elseif($task->priority === 'medium')
            <p class="text-yellow-600 priority-medium">Priority: {{ $task->priority }}</p>
            @elseif($task->priority === 'high')
            <p class="text-red-600 priority-high">Priority: {{ $task->priority }}</p>
            @endif
            <p class="text-gray-600">Category: {{ $task->category }}</p>
            <p class="text-gray-600">Due Date: {{ $task->due_date ? \Carbon\Carbon::parse($task->due_date)->format('Y-m-d') : 'Not set' }}</p>
        </div>
        @if($task->attachment)
        <div class="mb-4">
            <img id="attachment-preview" src="{{ asset('storage/uploads/'.$task->attachment) }}" class="h-60 w-full" alt="">
        </div>
        @endif
        <form action="{{ route('comments.store') }}" method="post" enctype="multipart/form-data">
            @csrf
            <input hidden type="text" name="task_id" value="{{ $task->id }}">



            <div class="mb-4">
                <label for="content" class="block text-sm font-medium text-gray-600">Add Comment</label>
                <textarea name="content" id="content" placeholder="type your comment" class="mt-1 p-2 w-full border rounded-md" required></textarea>
            </div>

            <button class="btn btn-success text-white">ADD Comment</button>
        </form>
    </div>
    <div>

    </div>
    <div class="w-2/4 mx-auto p-6">
        <h3 class="text-xl font-semibold">Comments</h3>
        <div id="comments-container" class="mb-4" style="max-height: 600px; overflow-y: auto;">
            <div class="sticky top-0 bg-white z-10">
                @forelse($comments as $comment)
                <div class="chat chat-start">
                    <div class="chat-image avatar">
                        <div class="w-10 rounded-full">
                            @if ($comment->user->image)
                            <img alt="User Avatar" src="{{ asset($comment->user->image) }}" />
                            @else
                            <img alt="Default Avatar" src="{{ asset('assets/images/avater.png') }}" />
                            @endif
                        </div>
                    </div>
                    <div class="chat-header">
                        {{ $comment->user->name }}
                        <time class="text-xs opacity-50">{{ $comment->created_at->format('H:i') }}</time>
                    </div>
                    <div class="chat-bubble bg-blue-400 text-white">{{ $comment->content }}</div>
                </div>
                @empty
                <div class="chat chat-start">
                    <div class="chat-image avatar">
                        <div class="w-10 rounded-full">
                            <img alt="Default Avatar" src="{{ asset('assets/no-data.png') }}" />
                        </div>
                    </div>
                    <div class="chat-header">
                        No comments found
                    </div>
                </div>
                @endforelse
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