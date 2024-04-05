<?php

namespace App\Http\Controllers\Administrator;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use App\Models\Task;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Role;
use Yajra\DataTables\Facades\DataTables;

class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    // app/Http/Controllers/TaskController.php


    public function index(Request $request)
    {
        $query = Task::query();

        // Check if the user is an administrator
        if (!auth()->user()->hasRole('administrator')) {
            // If not an administrator, show only tasks of the authenticated user
            $query->where('user_id', auth()->id());
        }

        // Check if the request includes a filter for deleted tasks
        if ($request->has('show_deleted')) {
            // If the user wants to view deleted tasks, fetch only soft-deleted tasks
            $query->onlyTrashed();
        } else {
            // Otherwise, fetch non-deleted tasks
            $query->with('user');
        }

        // Check if the request includes a filter for task priority
        if ($priority = $request->get('priority_filter')) {
            $query->where('priority', $priority);
        }

        if ($request->ajax()) {
            return DataTables::of($query)
            ->addColumn('actions', function ($task) {
                $softDeleteUrl = route('task.destroy', $task->id) . '?soft_delete=true'; // Adding soft_delete parameter
                return '
                    <a href="' . route('task.show', $task->id) . '" class="btn" style="background-color: yellow">
                    <img src="' . asset('assets/images/svg/eye-regular.svg') . '" style="filter: invert(100%);" class="w-4" alt="user-svg">
                    </a>
                    <a href="' . route('task.edit', $task->id) . '" class="btn" style="background-color: green">
                    <img src="' . asset('assets/images/svg/pencil-solid.svg') . '" style="filter: invert(100%);" class="w-4" alt="user-svg">
                    </a>
                    <button type="button" name="soft_delete" class="btn btn-sm delete-btn" style="background-color: red" data-url="' . $softDeleteUrl . '">
                        <img src="' . asset('assets/images/svg/trash-solid.svg') . '" style="filter: invert(100%);" class="w-4" alt="user-svg">
                    </button>';
            })
            ->rawColumns(['actions'])
                ->toJson();
        }

        return view('task.index');
    }




    

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $users = User::all();
        return view('task.create', compact('users'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'user_id' => 'required|exists:users,id',
            'description' => 'nullable|string',
            'priority' => 'required|in:low,medium,high',
            'category' => 'required|in:work,personal',
            'due_date' => 'nullable|date',
            'attachment' => 'nullable|file|mimes:jpeg,png,gif,pdf,doc,docx|max:2048',
        ]);
        $task = Task::create($validatedData);

        if ($request->hasFile('attachment')) {
            $fileName = $request->file('attachment')->getClientOriginalName();
            $imagePath = $request->file('attachment')->storeAs('public/uploads/attachment', $fileName);
            $task->attachment = 'attachment/' . $fileName; 
        }
        $task->save();
        return redirect()->route('task.index')->with('success', 'Task added successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $task = Task::findOrFail($id);
        $comments = $task->comments;
        return view('task.show', compact('task','comments'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $task = Task::findOrFail($id);
        $users = User::all();
        return view('task.update', compact('task','users'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Task $task)
    {
        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'user_id' => 'required|exists:users,id',
            'description' => 'nullable|string',
            'priority' => 'required|in:low,medium,high',
            'category' => 'required|in:work,personal',
            'due_date' => 'nullable|date',
            'attachment' => 'nullable|file|mimes:pdf,doc,docx|max:2048', 
        ]);
        $task->update($validatedData);
        return redirect()->route('task.index')->with('success', 'Task updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Task $task, Request $request)
{
    if ($request->has('soft_delete') && $request->soft_delete == 'true') {
        // Soft delete
        $task->delete();
        return redirect()->back()->with('success', 'Task soft deleted successfully');
    } else {
        // Hard delete
        $task->forceDelete();
        return redirect()->back()->with('success', 'Task permanently deleted successfully');
    }
}

}
