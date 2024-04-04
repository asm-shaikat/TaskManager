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
    if (auth()->user()->hasRole('administrator')) {
        $query = Task::with('user'); 
    } else {
        $query = Task::where('user_id', auth()->id())->with('user'); // Eager loading the user relationship
    }

    // Use DataTables to handle the request
    if ($request->ajax()) {
        return DataTables::of($query)
            ->addColumn('actions', function ($task) {
                return '
                    <a href="' . route('task.show', $task->id) . '" class="btn" style="background-color: yellow">
                    <img src="' . asset('assets/images/svg/eye-regular.svg') . '" style="filter: invert(100%);" class="w-4" alt="user-svg">
                    </a>
                    <a href="' . route('task.edit', $task->id) . '" class="btn" style="background-color: green">
                    <img src="'.asset('assets/images/svg/pencil-solid.svg').'" style="filter: invert(100%);" class="w-4" alt="user-svg">
                    </a>
                    <button type="button" class="btn btn-sm delete-btn" style="background-color: red" data-url="' . route('task.destroy', $task->id) . '">
                        <img src="'.asset('assets/images/svg/trash-solid.svg').'" style="filter: invert(100%);" class="w-4" alt="user-svg">
                    </button>';
            })
            ->rawColumns(['actions'])
            ->toJson();
    }
    $tasks = $query->get();
    $tasksCount = $tasks->count();
    return view('task.index', compact('tasksCount', 'tasks'));
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
    public function destroy(Task $task)
    {
        $task->delete();
        return redirect()->back()->with('success', 'Task deleted successfully');
    }
}
