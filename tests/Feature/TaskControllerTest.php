<?php

use App\Models\Task;

it('returns list of tasks', function () {
    Task::factory()->count(6)->create();

    $response = $this->getJson('/api/tasks');

    $response
        ->assertStatus(200)
        ->assertJsonCount(6);
});

it('shows a single task, positive', function () {
    $task = Task::factory()->create();

    $response = $this->getJson("/api/tasks/{$task->id}");

    $response
        ->assertStatus(200)
        ->assertJsonFragment([
            'id' => $task->id,
            'title' => $task->title,
            'description' => $task->description,
            'status' => $task->status,
        ]);
});

it('shows a single task, negative', function () {
    $nonExistentId = 333;

    $response = $this->getJson("/api/tasks/{$nonExistentId}");

    $response
        ->assertStatus(404)
        ->assertJsonStructure([
            'message',
        ]);
});
