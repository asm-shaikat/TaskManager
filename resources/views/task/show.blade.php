@extends('welcome')
@section('title','Task View')
@section('content')
<div class="flex w-full">
    <div class="w-3/4 ml-4">
        <div class="mb-4">
            <h3 class="text-2xl font-semibold inline-flex">{{ $task->title }}</h3>


            <div class="mb-4">
                <select id="status-select" class="form-select bg-green-700 text-white badge" onchange="updateTaskStatus(this.value)">
                    <option value="todo" {{ $task->status === 'todo' ? 'selected' : '' }}>To Do</option>
                    <option value="backlog" {{ $task->status === 'backlog' ? 'selected' : '' }}>Backlog</option>
                    <option value="in_progress" {{ $task->status === 'in_progress' ? 'selected' : '' }}>In Progress</option>
                    <option value="in_review" {{ $task->status === 'in_review' ? 'selected' : '' }}>In Review</option>
                    <option value="done" {{ $task->status === 'done' ? 'selected' : '' }}>Done</option>
                    <option value="achieved" {{ $task->status === 'achieved' ? 'selected' : '' }}>Achieved</option>
                </select>
            </div>
        </div>

        <div class="mb-4">
            <small class="text-gray-800 text-base">{{ $task->description }}</small>
        </div>

        <div>
            @foreach ($tags->labels as $label)
            <span class="bg-slate-200 rounded-lg text-xs badge">{{ $label->label }}</span>
            @endforeach
        </div>

        <div class="dropdown inline-block relative">
            <h4 class="text-lg font-semibold inline">Assigned to:</h4>
            <span class="cursor-pointer bg-cyan-700 text-white badge inline" id="user-name" onclick="toggleDropdown()">{{ $task->user->name }}</span>
            <div id="user-dropdown" class="hidden absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg z-10">
                <select id="user-select" class="form-select bg-white border" onchange="selectUser(this.value)">
                    @foreach($users as $user)
                    <option value="{{ $user->name }}" {{ $task->user->name === $user->name ? 'selected' : '' }}>{{ $user->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="mb-4">
            <div class="mb-4 flex items-center">
                <h4 class="text-lg font-semibold mr-2">Priority:</h4>
                <div class="flex items-center">
                    <select id="priority-select" class="form-select bg-white border" onchange="updateTaskPriority(this.value)">
                        <option value="low" class="priority-low" {{ $task->priority === 'low' ? 'selected' : '' }}>Low</option>
                        <option value="medium" class="priority-medium" {{ $task->priority === 'medium' ? 'selected' : '' }}>Medium</option>
                        <option value="high" class="priority-high" {{ $task->priority === 'high' ? 'selected' : '' }}>High</option>
                    </select>
                </div>
            </div>


            <div class="mb-4 flex items-center">
                <h4 class="text-lg font-semibold mr-2">Category:</h4>
                <div class="flex items-center">
                    <select id="category-select" class="form-select bg-white border" onchange="updateTaskCategory(this.value)">
                        <option value="work" {{ $task->category === 'work' ? 'selected' : '' }}>Work</option>
                        <option value="personal" {{ $task->category === 'personal' ? 'selected' : '' }}>Personal</option>
                    </select>
                </div>
            </div>

            <div class="mb-4 relative w-1/3">
                <label for="due_date" class="block text-sm font-medium text-gray-600">Due Date</label>
                <div class="flex items-center border rounded-md">
                    <input type="text" name="due_date" id="datepicker" class="mt-1 p-2 rounded-md focus:outline-none" placeholder="Select Due Date" value="{{ $task->due_date ? \Carbon\Carbon::parse($task->due_date)->format('Y-m-d') : '' }}" onchange="updateTaskDueDate(this.value)">
                    <span id="datepicker-icon" class="absolute right-0 mr-2 cursor-pointer">
                        <i class="fas fa-calendar text-green-500"></i>
                    </span>
                </div>
            </div>

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
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>
<script>
    // Update status
    function updateTaskStatus(status) {
        var taskId = '{{$task-> id}}';
        $.ajax({
            type: 'PUT',
            url: '/task/' + taskId,
            data: {
                _token: '{{ csrf_token() }}',
                status: status
            },
            success: function(response) {
                // Update UI or show success message
            },
            error: function(xhr, status, error) {
                // Handle error
            }
        });
    }
    // End of Update Status 
    // Update priority status
    // Show dropdown on click
    document.getElementById('priority-select').addEventListener('click', function(event) {
        event.stopPropagation(); // Stop the click event from propagating
        document.getElementById('priority-dropdown').classList.toggle('hidden');
    });

    // Function to update task priority via AJAX
    function updateTaskPriority(priority) {
        var taskId = '{{ $task->id }}';
        $.ajax({
            type: 'PUT',
            url: '/task/' + taskId + '/update-priority',
            data: {
                _token: '{{ csrf_token() }}',
                priority: priority
            },
            success: function(response) {
                // Handle success response or UI update
                document.getElementById('priority-select').value = priority; // Update the selected option
                document.getElementById('priority-dropdown').classList.add('hidden'); // Hide the dropdown
            },
            error: function(xhr, status, error) {
                // Handle error
            }
        });
    }

    // Add an event listener to detect clicks outside of the priority dropdown
    document.addEventListener('click', function(event) {
        var dropdown = document.getElementById('priority-dropdown');
        // Check if the clicked element is not part of the dropdown or select element
        if (!dropdown.contains(event.target) && event.target !== document.getElementById('priority-select')) {
            dropdown.classList.add('hidden'); // Hide the dropdown
        }
    });

    // End of Update Status
    // Start Update Category
    function updateTaskCategory(category) {
        var taskId = '{{ $task->id }}';
        $.ajax({
            type: 'PUT',
            url: '/task/' + taskId + '/update-category',
            data: {
                _token: '{{ csrf_token() }}',
                category: category
            },
            success: function(response) {
                // Handle success response or UI update
            },
            error: function(xhr, status, error) {
                // Handle error
            }
        });
    }


    // End  Update Category
    // Select2
    $(document).ready(function() {
        $('.js-example-basic-single').select2();
        $('#category').select2();
    });
    // End of Select2
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

    // Start due date
    function updateTaskDueDate(dueDate) {
        var taskId = '{{ $task->id }}';
        $.ajax({
            type: 'PUT',
            url: '/task/' + taskId + '/update-due-date',
            data: {
                _token: '{{ csrf_token() }}',
                due_date: dueDate
            },
            success: function(response) {
                // Handle success response or UI update
            },
            error: function(xhr, status, error) {
                // Handle error
            }
        });
    }
    // End due date

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
    // Show dropdown on click
    document.getElementById('user-name').addEventListener('click', function() {
        document.getElementById('user-dropdown').classList.toggle('hidden');
    });

    // Function to update user name via AJAX
    function updateUserName(newName) {
        var taskId = '{{ $task->id }}';
        $.ajax({
            type: 'PUT',
            url: '/task/' + taskId + '/update-user-name',
            data: {
                _token: '{{ csrf_token() }}',
                name: newName
            },
            success: function(response) {
                // Update UI or show success message
                document.getElementById('user-name').innerText = newName;
                toggleDropdown();
            },
            error: function(xhr, status, error) {
                // Handle error
            }
        });
    }
    // Assigning user
    function selectUser(userName) {
        var taskId = '{{ $task->id }}';
        $.ajax({
            type: 'PUT',
            url: '/task/' + taskId + '/update-user-name',
            data: {
                _token: '{{ csrf_token() }}',
                name: userName
            },
            success: function(response) {
                document.getElementById('user-name').innerText = userName;
                toggleDropdown();
            },
            error: function(xhr, status, error) {
                console.error(error);
            }
        });
    }

    // Add an event listener to detect clicks outside of the dropdown
    document.addEventListener('click', function(event) {
        var dropdown = document.getElementById('user-dropdown');
        // Check if the clicked element is not part of the dropdown
        if (!dropdown.contains(event.target) && event.target !== document.getElementById('user-name')) {
            dropdown.classList.add('hidden'); // Hide the dropdown
        }
    });
</script>
@endsection