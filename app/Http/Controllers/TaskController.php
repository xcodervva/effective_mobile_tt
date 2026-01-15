<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    //GET /tasks
    public function index()
    {
        return Task::all();
    }

    //GET /tasks/{id}
    public function show($id)
    {
        return Task::findOrFail($id);
    }

    //POST /tasks
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string',
            'description' => 'nullable|string',
            'status' => 'required|string',
        ]);

        return Task::create($validated);
    }

    //PUT /tasks{id}
    public function update(Request $request, $id)
    {
        $task = Task::findOrFail($id);

        $validated = $request->validate([
            'title' => 'required|string',
            'description' => 'nullable|string',
            'status' => ['required', 'string', 'in:active,done,new']
        ]);

        $task->update($validated);

        return $task;
    }

    //DELETE /tasks/{id}
    public function destroy($id)
    {
        Task::findOrFail($id)->delete();

        return response()->json(['message' => 'Task deleted']);
    }
}
