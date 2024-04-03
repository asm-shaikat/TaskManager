<?php

namespace App\Http\Controllers\Administrator;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use App\Models\Task;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index($taskId)
    {
        $task = Task::findOrFail($taskId);
        $comments = $task->comments;

        return view('comments.index', compact('comments'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'content' => 'required|string',
        ]);

        $comment = new Comment([
            'content' => $request->input('content'),
            'task_id' => $request->input('task_id'),
            'user_id' => auth()->id(),
        ]);

        if ($request->hasFile('attachment')) {
            $request->validate([
                'attachment' => 'nullable|file|mimes:jpeg,png,gif,pdf,doc,docx|max:2048',
            ]);

            $file = $request->file('attachment');
            $extension = $file->getClientOriginalExtension();

            $fileName = 'task_attachment_' . time() . '.' . $extension;
            $location = '/uploads/attachment/';

            $file->move(public_path() . $location, $fileName);

            $attachmentLocation = $location . $fileName;

            $comment->attachments = $attachmentLocation;
        }

        $comment->save();

        return redirect()->route('task.show', $request->input('task_id'))->with('success', 'Comment added successfully');
    }



    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
