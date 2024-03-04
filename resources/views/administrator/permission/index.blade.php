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
                @foreach($permissions as $singlePermission)
                <tr id="permissionRow_{{ $singlePermission->id }}">
                    <td class="py-2 px-4 border-b">{{ $singlePermission->name }}</td>
                    <td class="flex justify-evenly items-center p-4">
                        <a href="{{ route('permission.edit',$singlePermission->id) }}"><button class="bg-yellow-500 hover:bg-yellow-700 text-white font-bold py-2 px-4 rounded">Edit</button></a> 
                        <form action="{{ route('permission.destroy',$singlePermission->id) }}" method="post" onsubmit="return confirm('Are you sure?')">
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

</div>
@endsection