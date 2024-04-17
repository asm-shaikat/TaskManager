<?php

namespace App\Http\Controllers\Administrator;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use App\Models\Label as Tag;
use App\Models\LabelTask;
use App\Models\Task;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use PhpParser\Node\Stmt\Label;
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
    $user = Auth()->user();
    if (!auth()->user()->hasRole('administrator')) {
        $query->where('user_id', auth()->id());
    }

    if ($request->has('show_deleted')) {
        $query->onlyTrashed()->where('is_deleted', '=', 0)->with('user');
    } else {
        $query->with('user');
    }

    // Filter tasks by priority if the 'priority_filter' parameter is present
    if ($request->has('priority_filter')) {
        $priority = $request->input('priority_filter');
        if (!empty($priority)) {
            $query->where('priority', $priority);
        }
    }


    if ($request->ajax()) {
        return DataTables::of($query)
            ->addColumn('actions', function ($task) use ($request, $user) {
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
                    if ($user->can('task list')) {
                        $actions = '
                        <a href="' . route('task.show', $task->id) . '" class="btn" style="background-color: yellow;">
                            <img src="' . asset('assets/images/svg/eye-regular.svg') . '" class="w-4" alt="user-svg">
                        </a>';
                    }
                    if ($user->can('update task')) {
                        $actions .= '
                        <a href="' . route('task.edit', $task->id) . '" class="btn bg-blue-500">
                            <img src="' . asset('assets/images/svg/pencil-solid.svg') . '" style="filter: invert(100%);" class="w-4" alt="user-svg">
                        </a>';
                    }

                    // Check if the user has the permission to delete the task
                    if ($user->can('delete task')) {
                        $actions .= '
                        <form action="' . route('task.destroy', $task->id) . '" method="POST" style="display: inline;">
                            ' . csrf_field() . '
                            ' . method_field('DELETE') . '
                            <input type="hidden" name="soft_delete" value="true">
                            <button type="submit" name="soft_delete" class="btn delete-btn" style="background-color: red">
                                <img src="' . asset('assets/images/svg/trash-solid.svg') . '"  class="w-4" style="filter: invert(100%);" alt="user-svg">
                            </button>
                        </form>';
                    }

                    return $actions;
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
             'lebel' => 'nullable', 
             'lebel.*' => 'nullable|string|max:255',
         ]);
     
         // Create the task
         $task = Task::create($validatedData);
     
         // Upload attachment if provided
         if ($request->hasFile('attachment')) {
             $fileName = $request->file('attachment')->getClientOriginalName();
             $imagePath = $request->file('attachment')->storeAs('public/uploads/attachment', $fileName);
             $task->attachment = 'attachment/' . $fileName;
         }
     
         // Save the task
         $task->save();

        // Process labels
        if (!empty($validatedData['lebel'])) {
            // Decode the JSON string into an array
            $labelsData = json_decode($validatedData['lebel'], true);

            // Check if decoding was successful
            if (is_array($labelsData)) {
                // Extract label values from the array of objects
                $labelValues = array_column($labelsData, 'value');

                // Loop through each label value
                foreach ($labelValues as $label) {
                    // Trim the label and remove any leading or trailing whitespace
                    $label = trim($label);

                    // Skip empty labels
                    if (empty($label)) {
                        continue;
                    }

                    // Check if the label already exists in the database
                    $existingLabel = Tag::where('label', $label)->first();

                    // If the label doesn't exist, create it
                    if (!$existingLabel) {
                        $existingLabel = Tag::create(['label' => $label]);
                    }

                    // Associate the label with the task in the pivot table
                    $labelTask = new LabelTask();
                    $labelTask->task_id = $task->id;
                    $labelTask->label_id = $existingLabel->id;
                    $labelTask->save();
                }
            }
        }

            
     
         return redirect()->route('task.index')->with('success', 'Task added successfully!');
     }
     

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {   
        $users = User::all();
        $task = Task::findOrFail($id);
        $comments = $task->comments;
        $tags = Task::with('labels')->findOrFail($id);
        return view('task.show', compact('users','task', 'comments','tags'));
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
        'lebel' => 'nullable|array',
        'lebel.*' => 'nullable|string|max:255',
    ]);

    if ($request->has('status')) {
        $task->status = $request->status;
    }

     // Upload attachment if provided
     if ($request->hasFile('attachment')) {
        $fileName = time() . '_' . uniqid() . '.' . $request->file('attachment')->getClientOriginalExtension();
        $imagePath = $request->file('attachment')->storeAs('public/uploads/attachment', $fileName);
        $task->attachment = 'attachment/' . $fileName;
    }
    
    $task->save();
    // Update the task with validated data
    $task->update($validatedData);

    // Process labels
    if (!empty($validatedData['lebel'])) {
        // Decode the JSON string into an array
        $labelsData = $validatedData['lebel'];

        // Extract label values from the array of objects
        $labelValues = array_column($labelsData, 'value');

        // Sync the labels for the task
        $task->labels()->detach(); // Remove existing labels
        foreach ($labelValues as $label) {
            // Trim the label and remove any leading or trailing whitespace
            $label = trim($label);

            // Skip empty labels
            if (empty($label)) {
                continue;
            }

            // Check if the label already exists in the database
            $existingLabel = Tag::where('label', $label)->first();

            // If the label doesn't exist, create it
            if (!$existingLabel) {
                $existingLabel = Tag::create(['label' => $label]);
            }

            // Associate the label with the task in the pivot table
            $task->labels()->attach($existingLabel->id);
        }
    }

    return redirect()->route('task.index')->with('success', 'Task updated successfully!');
}

    public function updateUserName(Request $request, Task $task)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
        ]);

        $task->user->name = $request->name;
        $task->user->save();

        return response()->json(['message' => 'User name updated successfully'], 200);
    }

    public function updateStatus(Request $request, Task $task)
    {
        $request->validate([
            'status' => ['required', 'string', 'in:todo,in_progress,backlog,in_review,done,achieved'],
        ]);

        $task->status = $request->status;
        $task->save();

        return response()->json(['message' => 'Task status updated successfully'], 200);
    }


    public function updatePriority(Request $request, Task $task)
    {
        $request->validate([
            'priority' => ['required', 'string', 'in:low,medium,high'],
        ]);

        $task->priority = $request->priority;
        $task->save();

        return response()->json(['message' => 'Task priority updated successfully'], 200);
    }


    public function updateCategory(Request $request, Task $task)
    {
        $request->validate([
            'category' => ['required', 'string', 'in:work,personal'],
        ]);

        $task->category = $request->category;
        $task->save();

        return response()->json(['message' => 'Task category updated successfully'], 200);
    }

    public function updateDueDate(Request $request, Task $task)
    {
        $request->validate([
            'due_date' => ['nullable', 'date'],
        ]);

        $task->due_date = $request->due_date;
        $task->save();

        return response()->json(['message' => 'Task due date updated successfully'], 200);
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
