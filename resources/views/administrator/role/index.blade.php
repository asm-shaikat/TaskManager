@extends('welcome')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-5">
    <div class="flex justify-between items-center mb-4">
        <h2 class="text-2xl font-bold">Role Management</h2>
        <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded" onclick="my_modal_2.showModal()">Create Role</button>
    </div>

    <div class="overflow-x-auto">
        <table class="min-w-full bg-white border border-gray-300">
            <thead>
                <tr>
                    <th class="py-2 px-4 border-b">Name</th>
                    <th class="py-2 px-4 border-b">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($roles as $role)
                <tr>
                    <td class="py-2 px-4 border-b">{{ $role->name }}</td>
                    <td class="flex justify-evenly items-center p-4">
                        <button class="bg-yellow-500 hover:bg-yellow-700 text-white font-bold py-2 px-4 rounded" onclick="editRole('{{ $role->id }}', '{{ $role->name }}')">Edit</button>
                        <form action="{{ url('/administrator/role',$role->id) }}" onsubmit=" return confirm('Are you sure?')" method="post">
                            @method('DELETE')
                            @csrf
                            <button class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">Delete</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Create Modal -->
    <dialog id="my_modal_2" class="modal">
        <div class="modal-box">
            <h3 class="font-bold text-lg">Add Role</h3>

            <form action="{{ route('role.store') }}" method="post" class="py-4" enctype="multipart/form-data">
                @csrf
                <div class="mb-4">
                    <label for="postName" class="block text-sm font-medium text-gray-600">Role Name</label>
                    <input type="text" id="postName" name="postName" class="mt-1 p-2 border rounded-md w-full" required>
                </div>

                <div class="flex justify-between items-center">
                    <!-- Close button -->
                    <button onclick="my_modal_2.close()" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded mr-2">Close</button>
                    <!-- Submit button -->
                    <button class=" bg-green-500 hover:bg-green-900 text-white font-bold py-2 px-4 rounded mr-2">Submit</button>
                </div>
            </form>

            <p class="py-2">Press ESC key or click outside to close</p>
        </div>

        <form method="dialog" class="modal-backdrop" onclick="my_modal_2.close()"></form>
    </dialog>
    <!-- Create Modal End -->

    <!-- Edit Modal -->
    <dialog id="edit_modal" class="modal">
        <div class="modal-box">
            <h3 class="font-bold text-lg">Edit Role</h3>

            <form id="editForm" action="{{ route('role.update', ['role' => '__role_id__']) }}" method="post" class="py-4" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <input type="hidden" name="role_id" id="role_id">

                <div class="mb-4">
                    <label for="edit_role_name" class="block text-sm font-medium text-gray-600">Role Name</label>
                    <input type="text" id="edit_role_name" name="edit_role_name" class="mt-1 p-2 border rounded-md w-full" required>
                </div>

                <div class="flex justify-between items-center">
                    <button onclick="document.getElementById('edit_modal').close()" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded mr-2">Close</button>
                    <button class="bg-green-500 hover:bg-green-900 text-white font-bold py-2 px-4 rounded mr-2" onclick="updateRole()">Update</button>
                </div>
            </form>

            <div class="button-container">
                <p>Role Permission</p>
                @if($role->permissions)
                <div class="flex gap-1">
                @foreach($role->permissions as $role_permission)
                    <form action="{{ route('administrator.role.removePermissions', [$role->id, $role_permission->id]) }}" onsubmit="return confirm('Are you sure?')" method="post">
                        @method('DELETE')
                        @csrf
                        <button class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">{{ $role_permission->name }}</button>
                    </form>
                    @endforeach
                </div>
                @endif
            </div>
            <form id="editForm" action="{{ route('administrator.role.permissions', $role->id) }}" method="post" class="py-4" enctype="multipart/form-data">
                @csrf
                <div class="mb-4">
                    <label for="permission" class="block text-sm font-medium text-gray-600 p-2">Permission</label>
                    <select class="select select-bordered w-full" name="permission">
                        <option disabled selected>Select a permission</option>
                        @foreach($permissions as $permission)
                        <option>{{ $permission->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="flex justify-end items-center">
                    <button class="bg-green-500 hover:bg-green-900 text-white font-bold py-2 px-4 rounded mr-2" onclick="updateRole()">Update</button>
                </div>
            </form>


            <p class="py-2">Press ESC key or click outside to close</p>
        </div>

        <form method="dialog" class="modal-backdrop" onclick="document.getElementById('edit_modal').close()"></form>
    </dialog>
    <!-- Edit Modal End -->

</div>

@section('script')
<script>
    function editRole(id, name) {
        document.getElementById('role_id').value = id;
        document.getElementById('edit_role_name').value = name;
        var form = document.getElementById('editForm');
        form.action = form.action.replace('__role_id__', id);
        document.getElementById('edit_modal').showModal();
    }

    function updateRole() {
        document.getElementById('editForm').submit();
    }
</script>
@endsection
@endsection