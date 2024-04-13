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

        if (!auth()->user()->hasRole('administrator')) {
            $query->where('user_id', auth()->id());
        }

        if ($request->has('show_deleted')) {
            $query->onlyTrashed()->where('is_deleted', '=', 0)->with('user');
        } else {
            $query->with('user');
        }

        if ($priority = $request->get('priority_filter')) {
            $query->where('priority', $priority);
        }

        if ($request->ajax()) {
            return DataTables::of($query)
                ->addColumn('actions', function ($task) use ($request) {
                    if ($task->deleted_at) {
                        return '
                        <a href="' . route('task.restore',$task->id) . '" class="btn" style="background-color: cyan">
                            <img src="' . asset('assets/images/svg/restore.svg') . '"class="w-4" alt="user-svg">
                        </a>
                        <form action="' . route('task.hardDelete', $task->id) . '" method="POST" style="display: inline;">
                            ' . csrf_field() . '
                            ' . method_field('PUT') . '
                            <button type="submit" name="hard_delete" class="btn delete-btn" style="background-color: red">
                                <img src="' . asset('assets/images/svg/trash-solid.svg') . '"  class="w-4" style="filter: invert(100%);" alt="user-svg">
                            </button>
                        </form>
                        ';
                    } else {
                        // Task is not soft-deleted, provide regular delete button
                        return '
                        <a href="' . route('task.show', $task->id) . '" class="btn" style="background-color: yellow;">
                            <img src="' . asset('assets/images/svg/eye-regular.svg') . '" class="w-4" alt="user-svg">
                        </a>
                        <a href="' . route('task.edit', $task->id) . '" class="btn bg-blue-500">
                            <img src="' . asset('assets/images/svg/pencil-solid.svg') . '" style="filter: invert(100%);" class="w-4" alt="user-svg">
                        </a>
                        <form action="' . route('task.destroy', $task->id) . '" method="POST" style="display: inline;">
                            ' . csrf_field() . '
                            ' . method_field('DELETE') . '
                            <input type="hidden" name="soft_delete" value="true">
                            <button type="submit" name="soft_delete" class="btn delete-btn" style="background-color: red">
                                <img src="' . asset('assets/images/svg/trash-solid.svg') . '"  class="w-4" style="filter: invert(100%);" alt="user-svg">
                            </button>
                        </form>';
                    }
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
        return view('task.show', compact('task', 'comments'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $task = Task::findOrFail($id);
        $users = User::all();
        return view('task.update', compact('task', 'users'));
    }

    /**$task
     * Update the specified resource in storage.
     */
    public function update(Request $request, Task $task)
    {
        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'user_id' => 'required|exists:users,id',
            'description' => 'nullable|string',
            'status' => 'sometimes',
            'priority' => 'required|in:low,medium,high',
            'category' => 'required|in:work,personal',
            'due_date' => 'nullable|date',
            'attachment' => 'nullable|file|mimes:pdf,doc,docx|max:2048',
        ]);

        if ($request->has('status')) {
            $task->status = $request->status;
        }
        
        $task->update($validatedData);
        return redirect()->route('task.index')->with('success', 'Task updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Task $task, Request $request)
    {
            // Soft delete
            $task->delete();
            return redirect()->back()->with('success', 'Task soft deleted successfully');
    }

    public function hardDelete(string $id) {
        try {
            $task = Task::withTrashed()->find($id);
            $task->update(['is_deleted' => true]);
            return redirect()->back()->with('success', 'Task permanently deleted');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to delete task. Please try again.');
        }
    }
    
    


    // Task restoring function
    public function restore(string $id)
    {   
        $task = Task::withTrashed()->find($id);
        $task->restore(); 
        return redirect()->back();
    }
}
