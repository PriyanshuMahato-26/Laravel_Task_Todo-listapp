<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    /**
     * Display the list of tasks
     */
    public function index()
    {
        $tasks = Task::all();
        return view('tasks.index', compact('tasks'));
    }

    /**
     * Store a newly created task
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
        ]);

        // Check for duplicates
        $existingTask = Task::where('title', $request->title)->first();
        
        if ($existingTask) {
            return response()->json([
                'error' => 'Task already exists'
            ], 422);
        }

        $task = Task::create([
            'title' => $request->title,
            'completed' => false
        ]);

        return response()->json($task);
    }

    /**
     * Update the specified task status
     */
    public function update(Request $request, Task $task)
    {
        $task->update([
            'completed' => $request->completed
        ]);

        return response()->json($task);
    }

    /**
     * Remove the specified task
     */
    public function destroy(Task $task)
    {
        $task->delete();
        return response()->json(['success' => true]);
    }

    /**
     * Get all tasks (both completed and non-completed)
     */
    public function getAllTasks()
    {
        $tasks = Task::all();
        return response()->json($tasks);
    }
}