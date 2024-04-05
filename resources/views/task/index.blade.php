@extends('welcome')
@section('title', 'Task List')
@section('content')
<div class="max-w-full mx-auto mt-8 p-6 bg-white rounded-md shadow-md">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-semibold">My Tasks</h2>
        @can('create task')
        <div>
            <a href="{{ route('task.create') }}">
                <button class="btn h-10 bg-blue-500 hover:bg-blue-700 text-white">
                    <span class="mr-2">Create</span>
                    <img src="{{ asset('assets/images/svg/plus-solid.svg') }}" class="w-4" alt="">
                </button>
            </a>
        </div>
        @endcan
    </div>

    <div class="flex mb-4 items-center">
        <!-- Priority filtering -->
        <div class="w-1/4 mr-4">
            <label for="priority_filter" class="block text-sm font-medium text-gray-700">Filter by Priority:</label>
            <select id="priority_filter" name="priority_filter" class="mt-1 h-12 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                <option value="">All Priorities</option>
                <option value="low">Low</option>
                <option value="medium">Medium</option>
                <option value="high">High</option>
            </select>
        </div>
        <!-- End of priority filtering -->
        <!-- Due date -->
        <div class="relative w-1/4 mr-4">
            <label for="due_date_start" class="block text-sm font-medium text-gray-600">Due Date Start:</label>
            <div class="flex items-center border rounded-md">
                <input type="text" name="due_date_start" id="due_date_start" class="mt-1 p-2 w-full rounded-md focus:outline-none" placeholder="Select Start Date" value="{{ old('due_date_start') }}">
                <span id="datepicker-icon-start" class="absolute right-0 mr-2 cursor-pointer">
                    <i class="fas fa-calendar text-green-500"></i>
                </span>
            </div>
        </div>
        <div class="relative w-1/4 mr-4">
            <label for="due_date_end" class="block text-sm font-medium text-gray-600">Due Date End:</label>
            <div class="flex items-center border rounded-md">
                <input type="text" name="due_date_end" id="due_date_end" class="mt-1 p-2 w-full rounded-md focus:outline-none" placeholder="Select End Date" value="{{ old('due_date_end') }}">
                <span id="datepicker-icon-end" class="absolute right-0 mr-2 cursor-pointer">
                    <i class="fas fa-calendar text-green-500"></i>
                </span>
            </div>
        </div>
        <!-- End due date filtering -->
        <!-- Reset button -->
        <div class="w-1/4 mt-4">
            <button class="btn text-white btn-accent" id="resetBtn">Reset</button>
        </div>
    </div>


    <div class="mb-4">
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
        <p class="text-gray-600">No tasks assigned to you.</p>
    </div>
</div>
@endsection

@section('script')
<script>
    $(document).ready(function() {
        // Initialize date pickers
        flatpickr("#due_date_start, #due_date_end", {
            dateFormat: "Y-m-d",
        });

        var table = $('#yajraTaskTable').DataTable({
            serverSide: true,
            ajax: {
                url: '/task',
                type: 'GET'
            },
            columns: [{
                    data: 'title',
                    name: 'title',
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
            ],
            "lengthMenu": [
                [10, 25, 50, -1],
                [10, 25, 50, "All"]
            ]
        });

        $('#priority_filter').change(function() {
            var priority = $(this).val();
            table.column(2).search(priority).draw();
        });

        // Date range filtering
        $('#due_date_start, #due_date_end').on('change', function() {
            var startDate = $('#due_date_start').val();
            var endDate = $('#due_date_end').val();

            table.columns(3).search(startDate + '|' + endDate, true, false).draw();
        });
        //Reset date range filtering
        $('#resetBtn').on('click', function() {
        // Clear priority filter
        $('#priority_filter').val('');
        table.column(2).search('').draw();

        // Clear start and end date inputs
        $('#due_date_start').val('');
        $('#due_date_end').val('');
        table.columns(3).search('').draw();
    });

    // Delete button functionality
    $(document).on('click', '.delete-btn', function() {
    var url = $(this).data('url');
    Swal.fire({
        title: 'Are you sure?',
        text: 'You will not be able to recover this record!',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Yes, delete it!'
    }).then((result) => {
        if (result.isConfirmed) {
            var softDelete = $(this).data('soft-delete');
            var method = 'DELETE';
            if (softDelete === 'true') {
                // If soft delete is requested, change method to POST and add soft_delete parameter
                method = 'POST';
                url += '?soft_delete=true';
            }
            $.ajax({
                url: url,
                type: method,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    location.reload();
                },
                error: function(xhr) {
                    console.error(xhr.responseText);
                }
            });
        }
    });
});


    });
</script>
@endsection