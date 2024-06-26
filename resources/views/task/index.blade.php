@extends('welcome')
@section('title', 'Task List')
@section('content')
<div class="max-w-full mx-auto mt-8 p-6 bg-white rounded-md shadow-md" id="OrginalData">
    <div class="flex justify-between items-center">
        <h2 class="text-2xl font-semibold mb-2">My Tasks</h2>
        @can('create task')
        <div class="-mt-12 flex items-center"> <!-- Added flex and items-center here -->
            <a href="{{ route('task.create') }}">
                <button class="btn h-10 w-44 bg-slate-700 hover:bg-blue-700 text-white">
                    <span class="mr-2">Create</span>
                    <img src="{{ asset('assets/images/svg/plus-solid.svg') }}" style="filter: invert(100%);" class="w-4" alt="">
                </button>
            </a>
        </div>
        @endcan
    </div>

    <!-- List and Deleted buttons -->
    <div class="mb-6">
        <button class="btn mr-4 toggle-btn text-white bg-slate-700" data-target="OrginalData">List</button>
        <button class="btn toggle-btn" data-target="DeleteData">Deleted</button>
    </div>
    <div class="flex mb-4 items-center">
        <!-- Priority filtering -->
        <div class="w-1/4 mr-4">
            <label for="priority_filter" class="block text-sm font-medium text-gray-700">Filter by Priority:</label>
            <select id="priority_filter" name="priority_filter" style="height: 52px;" class="block w-full  px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
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
                <input type="text" name="due_date_start" id="due_date_start" class="mt-1 h-12 block w-full px-3 rounded-md focus:outline-none" placeholder="Select Start Date" value="{{ old('due_date_start') }}">
                <span id="datepicker-icon-start" class="absolute right-0 mr-2 cursor-pointer">
                    <i class="fas fa-calendar text-slate-600"></i>
                </span>
            </div>
        </div>
        <div class="relative w-1/4 mr-4">
            <label for="due_date_end" class="block text-sm font-medium text-gray-600">Due Date End:</label>
            <div class="flex items-center border rounded-md">
                <input type="text" name="due_date_end" id="due_date_end" class="mt-1 h-12 block w-full px-3 rounded-md focus:outline-none" placeholder="Select End Date" value="{{ old('due_date_end') }}">
                <span id="datepicker-icon-end" class="absolute right-0 mr-2 cursor-pointer">
                    <i class="fas fa-calendar text-slate-600"></i>
                </span>
            </div>
        </div>

        <!-- Custom searching -->
        <div class="w-1/4 mr-4">
            <label for="custom_search" class="block text-sm font-medium text-gray-700">Search:</label>
            <input type="text" id="custom_search" style="height: 52px;" class="h-12 block w-full px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
        </div>
        <!-- End Custom searching -->
        <!-- End due date filtering -->
        <!-- Reset button -->
        <div class="w-1/4 mt-4">
            <button class="btn text-white bg-slate-700" id="resetBtn">Reset</button>
        </div>
    </div>



    <div class="mb-4">
        <table class="min-w-full divide-y divide-gray-200" id="yajraTaskTable">
            <thead class="bg-gray-100">
                <tr>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Title</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Assigned to</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Priority</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Due Date</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody>

            </tbody>
        </table>
    </div>
</div>


<div class="max-w-full mx-auto mt-8 p-6 bg-white rounded-md shadow-md" id="DeleteData" style="display: none;">
    <div class="flex justify-between items-center mb-2">
        <h2 class="text-2xl font-semibold">My Tasks</h2>
        @can('create task')
        <div class="-mt-12">
            <a href="{{ route('task.create') }}">
                <button class="btn h-10 w-44 bg-slate-700 hover:bg-blue-700 text-white">
                    <span class="mr-2">Create</span>
                    <img src="{{ asset('assets/images/svg/plus-solid.svg') }}" style="filter: invert(100%);" class="w-4" alt="">
                </button>
            </a>
        </div>
        @endcan
    </div>
    <!-- List and Deleted buttons -->
    <div class="mb-6">
        <button class="btn mr-4 toggle-btn" data-target="OrginalData">List</button>
        <button class="btn toggle-btn text-white bg-slate-700" data-target="DeleteData">Deleted</button>
    </div>

    <!-- Delete data table -->
    <div class="mb-4">
        <table class="min-w-full  divide-y divide-gray-200" id="deletedTable">
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
    </div>

</div>
@endsection

@section('script')
<script>
    $(document).ready(function() {
        var table = $('#yajraTaskTable').DataTable({
            serverSide: true,
            ajax: {
                url: '/task',
                type: 'GET',
                data: function(data) {
                    data.priority_filter = $('#priority_filter').val();
                }
            },
            columns: [{
                    data: 'title',
                    name: 'title'
                },
                {
                    data: 'user.name',
                    name: 'user.name'
                },
                {
                    data: 'priority',
                    name: 'priority',
                    className: 'text-center capitalize'
                },
                {
                    data: 'status',
                    name: 'status',
                    render: function(data, type, row) {
                        if (data === "in_progress") {
                            return "In Progress";
                        }
                        if (data === "in_review") {
                            return "In Review";
                        }
                        return data;
                    },
                    className: 'text-center capitalize'
                },
                {
                    data: 'due_date',
                    name: 'due_date',
                    className: 'text-center'
                },
                {
                    data: 'actions',
                    name: 'actions',
                    className: 'text-center',
                    orderable: false,
                    searchable: false
                }
            ],
            lengthChange: false,
        });

        // Toggling between two section
        var deletedTable;
        $('.toggle-btn').click(function() {
            var target = $(this).data('target');
            $('#' + target).show();
            $('.max-w-full').not('#' + target).hide();
            $(this).addClass('active').siblings().removeClass('active');

            if (target === 'OrginalData') {
                table.ajax.url('/task').load(); // Reload the original table
            } else {
                if (!deletedTable) {
                    // Initialize the deletedTable DataTable if it hasn't been initialized yet
                    deletedTable = $('#deletedTable').DataTable({
                        serverSide: true,
                        ajax: {
                            url: '/task',
                            type: 'GET',
                            data: {
                                show_deleted: true
                            }
                        },
                        columns: [{
                                data: 'title',
                                name: 'title'
                            },
                            {
                                data: 'user.name',
                                name: 'user.name'
                            },
                            {
                                data: 'priority',
                                name: 'priority',
                                className: 'text-center'
                            },
                            {
                                data: 'due_date',
                                name: 'due_date',
                                className: 'text-center'
                            },
                            {
                                data: 'actions',
                                name: 'actions',
                                className: 'text-center',
                                orderable: false,
                                searchable: false
                            }
                        ],
                        lengthChange: false,
                    });
                } else {
                    // If the deletedTable DataTable is already initialized, just reload it
                    deletedTable.ajax.url('/task?show_deleted=true').load();
                }
            }
        });

        // priority filtering
        $('#priority_filter').on('change', function() {
            table.ajax.reload();
        });
        // Initialize date pickers
        flatpickr("#due_date_start, #due_date_end", {
            dateFormat: "Y-m-d",
        });

        // Priority filtering
        $('#priority_filter').on('change', function() {
            table.ajax.reload();
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

        $('#custom_search').on('keyup', function() {
            var searchTerm = $(this).val();
            table.search(searchTerm).draw(); // Apply the search term and redraw the DataTable
        });

        // Delete button functionality
        $(document).on('click', '.delete-btn', function(e) {
            e.preventDefault();
            var form = $(this).closest('form');
            var section = $(this).closest('.max-w-full').attr('id');

            var deleteType = 'soft_delete'; // Default delete type (soft delete)

            if (section === 'DeleteData') {
                // For hard delete
                deleteType = 'hard_delete';
            }

            // Set the value of the hidden input field
            $('#delete_type').val(deleteType);

            var confirmationText = 'Are you sure you want to delete this task?';
            if (deleteType === 'hard_delete') {
                confirmationText += ' This action will permanently delete the task.';
            }

            Swal.fire({
                title: 'Confirmation',
                text: confirmationText,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Yes, delete it'
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        });


    });
</script>
@endsection