@extends('welcome')
@section('title','Role')
@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-5">
    <div class="flex justify-between items-center mb-4">
        <h2 class="text-2xl font-bold">Role Management</h2>
        <a href="{{ route('role.create') }}">
            <button class="btn h-10 w-44 bg-slate-700 hover:bg-blue-700 text-white">
                <p class="p-4">Create</p>
                <img src="{{ asset('assets/images/svg/plus-solid.svg') }}" style="filter: invert(100%);" class="w-6" alt="">
            </button>
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
                <td class="border p-1">{{ $role->name }}</td>
                <td>
                    <div class="flex text-center p-1">
                        @foreach ($role->permissions as $permission)
                        <small class="bg-slate-700 m-1 p-2 rounded-xl text-white">{{ $permission->name }}</small>
                        @endforeach
                    </div>
                </td>
                <td class="border p-2 text-center">
                    <div class="flex gap-2">
                        <div class="">
                            <a href="{{ route('role.edit', $role->id) }}" class="text-blue-500 hover:underline">
                                <button class="btn bg-blue-500"><img src="{{ asset('assets/images/svg/pencil-solid.svg') }}" style="filter: invert(100%);" class="w-4" alt="user-svg"></button>
                            </a>
                        </div>
                        <div>
                            <form id="delete-role-{{ $role->id }}" action="{{ route('role.destroy', $role->id) }}" method="post" class="inline" >
                                @csrf
                                @method('DELETE')
                                <button type="button" class="btn" style="background-color: red;"   onclick="confirmDelete('delete-role-{{ $role->id }}')">
                                    <img src="{{ asset('assets/images/svg/trash-solid.svg') }}" style="filter: invert(100%);" class="w-4" alt="user-svg">
                                </button>
                            </form>
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
@endsection
<script>
// SweetAlert2
function confirmDelete(formId) {
    Swal.fire({
        title: 'Are you sure?',
        text: 'You will not be able to recover this record!',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Yes, delete it'
    }).then((result) => {
        if (result.isConfirmed) {
            document.getElementById(formId).submit();
        }
    });
}
// End SweetAlert2
</script>
