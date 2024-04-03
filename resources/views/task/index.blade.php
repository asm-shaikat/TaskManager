@extends('welcome')
@section('title','Task List')
@section('content')
<div class="max-w-full mx-auto mt-2 p-6 bg-white rounded-md shadow-md">
    <div class="flex justify-between">
        <div>
            <h2 class="text-2xl font-semibold mb-6">My Tasks</h2>
        </div>
        @can('create task')
        <div>
            <a href="{{ route('task.create') }}">
                <button class="btn h-10 w-44 bg-blue-500 hover:bg-blue-700 text-white">
                    <p class="p-4">Create</p>
                    <img src="{{ asset('assets/images/svg/plus-solid.svg') }}" style="filter: invert(100%);" class="w-6" alt="">
                </button>
            </a>
        </div>
        @endcan
    </div>

    <div class="mb-4">
        <p class="text-gray-600">You have {{ $tasksCount }} tasks assigned.</p>
    </div>

    <div class="mb-4">
        @if($tasksCount > 0)
        <table class="min-w-full divide-y divide-gray-200" id="yajraTaskTable">
            <thead class="bg-gray-100">
                <tr>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Title</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Assigned to</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Priority</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Due Date</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
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
            columns: [{
                    data: 'title',
                    name: 'title',
                    render: function(data, type, row) {
                        if (type === 'display') {
                            var url = '/task/' + row.id;
                            return '<a href="' + url + '" class="block p-2 text-blue-500 hover:underline">' + data + '</a>';
                        }
                        return data;
                    }
                },
                {
                    data: 'user.name',
                    name: 'user.name'
                },
                {
                    data: 'priority',
                    name: 'priority'
                },
                {
                    data: 'due_date',
                    name: 'due_date'
                },
                {
                    data: 'actions',
                    name: 'actions',
                    orderable: false,
                    searchable: false
                }
            ]
        });

        $('#yajraTaskTable tbody').on('click', 'td:first-child', function() {
            var data = $('#yajraTaskTable').DataTable().row($(this).closest('tr')).data();
            window.location.href = data.show_url;
        });
    });
</script>
@endsection