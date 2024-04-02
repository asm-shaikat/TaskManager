@extends('welcome')

@section('content')
<div class="max-w-full mx-auto mt-10 p-6 bg-white rounded-md shadow-md">
    <div class="flex justify-between">
        <div>
            <h2 class="text-2xl font-semibold mb-6">My Tasks</h2>
        </div>
        @can('create task')
        <div>
            <a href="{{ route('task.create') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Create Task</a>
        </div>
        @endcan
    </div>
    
    <div class="mb-4">
        <p class="text-gray-600">You have {{ $tasksCount }} tasks assigned.</p>
    </div>

    <div class="mb-4">
        @if($tasksCount > 0)
        <table class="w-full p-4 border display" id="yajraTaskTable">
            <thead>
                <tr>
                    <th class="p-2">Title</th>
                    <th class="p-2">Description</th>
                    <th class="p-2">Priority</th>
                    <th class="p-2">Due Date</th>
                    <th class="p-2">Actions</th>
                </tr>
            </thead>
            <tbody>
                
            </tbody>
        </table>
        @else
        <p class="text-gray-600">No tasks assigned to you.</p>
        @endif
    </div>
</div>
@endsection
@section('script')
<script>
$(document).ready(function() {
    $('#yajraTaskTable').DataTable({
        serverSide: true,
        ajax: {
            url: '/task',
            type: 'GET'
        },
        columns: [
            { data: 'title', name: 'title',
                render: function(data, type, row) {
                    if (type === 'display') {
                        var url = '/task/' + row.id;
                        return '<a href="' + url + '" class="block p-2 text-blue-500 hover:underline">' + data + '</a>';
                    }
                    return data;
                }
            },
            { data: 'description', name: 'description' },
            { data: 'priority', name: 'priority' },
            { data: 'due_date', name: 'due_date' },
            { data: 'actions', name: 'actions', orderable: false, searchable: false }
        ]
    });

    $('#yajraTaskTable tbody').on('click', 'td:first-child', function() {
        var data = $('#yajraTaskTable').DataTable().row($(this).closest('tr')).data();
        window.location.href = data.show_url;
    });
});    
</script>
@endsection