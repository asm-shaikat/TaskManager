@extends('welcome')
@section('title','Role')
@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-5">
    <div class="flex justify-between items-center mb-4">
        <h2 class="text-2xl font-bold">Role Management</h2>
        <a href="{{ route('role.create') }}">
            <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Create Role</button>
        </a>
    </div>

    <table class="w-full border">
        <thead>
            <tr>
                <th class="border p-2">Role Name</th>
                <th class="border p-2">Permission</th>
                <th class="border p-2">Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($roles as $role)
            <tr>
                <td class="border p-2">{{ $role->name }}</td>
                <td>
                    <div class="flex">
                        @foreach ($role->permissions as $permission)
                        <small class="bg-green-500 m-2 p-1 rounded-xl text-white">{{ $permission->name }}</small>
                        @endforeach
                    </div>
                </td>
                <td class="border p-2 text-center">
                    <div class="flex gap-2">
                        <div>
                            <a href="{{ route('role.edit', $role->id) }}" class="text-blue-500 hover:underline">
                                <img src="{{ asset('assets/images/svg/pencil-solid.svg') }}" class="w-4" alt="user-svg">
                            </a>
                        </div>
                        <div>
                            <a href="" class="text-blue-500 hover:underline">
                                <img src="{{ asset('assets/images/svg/trash-solid.svg') }}" class="w-4" alt="user-svg">
                            </a>
                        </div>
                    </div>
                </td>

            </tr>
            @empty
            <tr>
                <td class="border p-2" colspan="3">No roles found.</td>
            </tr>
            @endforelse
        </tbody>
    </table>

  

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