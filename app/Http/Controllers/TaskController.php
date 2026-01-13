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
}
