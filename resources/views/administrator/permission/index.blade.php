@extends('welcome')
@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-5">
    <div class="flex justify-between items-center mb-4">
        <h2 class="text-2xl font-bold">Permission Management</h2>
        <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded" onclick="my_modal_2.showModal()">Create Permission</button>
    </div>
    <div class="overflow-x-auto">
        <table class="min-w-full bg-white border border-gray-300">
            <thead>
                <tr>
                    <th class="py-2 px-4 border-b">Name</th>
                    <th class="py-2 px-4 border-b">Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach($permission as $singlePermission)
                <tr id="permissionRow_{{ $singlePermission->id }}">
                    <td class="py-2 px-4 border-b">{{ $singlePermission->name }}</td>
                    <td class="flex justify-evenly items-center p-4">
                        <button class="bg-yellow-500 hover:bg-yellow-700 text-white font-bold py-2 px-4 rounded" onclick="editPermission('{{ $singlePermission->id }}', '{{ $singlePermission->name }}')">Edit</button>
                        <button class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">Delete</button>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Modal -->
    <dialog id="my_modal_2" class="modal">
        <div class="modal-box">
            <h3 class="font-bold text-lg">Add Permission</h3>

            <form action="{{ route('permission.store') }}" method="post" class="py-4" enctype="multipart/form-data">
                @csrf
                <div class="mb-4">
                    <label for="permissionName" class="block text-sm font-medium text-gray-600">Permission Name</label>
                    <input type="text" id="permissionName" name="permissionName" class="mt-1 p-2 border rounded-md w-full" required>
                </div>

                <div class="flex justify-between items-center">
                    <button onclick="my_modal_2.close()" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded mr-2">Close</button>
                    <button class=" bg-green-500 hover:bg-green-900 text-white font-bold py-2 px-4 rounded mr-2">Submit</button>
                </div>
            </form>

            <p class="py-2">Press ESC key or click outside to close</p>
        </div>

        <form method="dialog" class="modal-backdrop" onclick="my_modal_2.close()"></form>
    </dialog>
    <!-- Modal End -->

    <!-- Update Modal -->
    <!-- Update Modal -->
    <dialog id="edit_modal" class="modal">
        <div class="modal-box">
            <h3 class="font-bold text-lg">Edit Permission</h3>

            <!-- Form for editing a permission -->
            <form id="updateForm" action="{{ route('permission.update', ['permission' => 'permission_id']) }}" method="post" class="py-4" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <input type="hidden" name="permission_id" id="permission_id">

                <div class="mb-4">
                    <label for="edit_permission_name" class="block text-sm font-medium text-gray-600">Permission Name</label>
                    <input type="text" id="edit_permission_name" name="edit_permission_name" class="mt-1 p-2 border rounded-md w-full" required>
                </div>

                <div class="flex justify-between items-center">
                    <button type="button" onclick="document.getElementById('edit_modal').close()" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded mr-2">Close</button>
                    <button id="updateButton" class="bg-green-500 hover:bg-green-900 text-white font-bold py-2 px-4 rounded mr-2" onclick="updatePermission()">Update</button>
                </div>
            </form>

            <p class="py-2">Press ESC key or click outside to close</p>
        </div>

        <form method="dialog" class="modal-backdrop" onclick="document.getElementById('edit_modal').close()"></form>
    </dialog>
    <!-- End Update Modal -->

    <!-- End Update Modal -->

</div>

@section('script')
<script>
    function editPermission(id, name) {
        document.getElementById('permission_id').value = id;
        document.getElementById('edit_permission_name').value = name;
        document.getElementById('updateButton').style.display = 'block'; // Show the Update button
        document.getElementById('edit_modal').showModal();

        document.getElementById('updateForm').action = "{{ route('permission.update', ['permission' => 'permission_id']) }}".replace('permission_id', id);
    }

    function updatePermission() {
        document.getElementById('updateForm').submit();
    }
</script>

@endsection
@endsection