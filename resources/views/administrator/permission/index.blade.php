@extends('welcome')
@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-5">
<div class="flex justify-between items-center mb-4">
        <h2 class="text-2xl font-bold">Permission Management</h2>
        <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Create Permission</button>
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
                @foreach($permission as $permission)
                <tr>
                    <td class="py-2 px-4 border-b">{{ $permission->name }}</td>
                    <td class="py-2 px-4 border-b"><button>Edit</button></td>
                    <td class="py-2 px-4 border-b"><button>Delete</button></td>
                </tr>                
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection