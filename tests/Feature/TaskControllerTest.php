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

it('creates a task', function () {
    $data = [
        'title' => 'Test task',
        'description' => 'Test description',
        'status' => 'new',
    ];

    $response = $this->postJson('/api/tasks', $data);

    $response
        ->assertStatus(201)
        ->assertJsonFragment(['title' => 'Test task']);

    $this->assertDatabaseHas('tasks', [
        'title' => 'Test task',
    ]);
});

it('updates a task, set status=done', function () {
    $task = Task::factory()->create();

    $response = $this->putJson("/api/tasks/{$task->id}", [
        'title' => 'Updated title',
        'description' => 'Updated',
        'status' => 'done',
    ]);

    $response->assertStatus(200);

    $this->assertDatabaseHas('tasks', [
        'id' => $task->id,
        'status' => 'done',
    ]);
});

it('updates a task, set status=active', function () {
    $task = Task::factory()->create();

    $response = $this->putJson("/api/tasks/{$task->id}", [
        'title' => 'Updated title',
        'description' => 'Updated',
        'status' => 'active',
    ]);

    $response->assertStatus(200);

    $this->assertDatabaseHas('tasks', [
        'id' => $task->id,
        'status' => 'active',
    ]);
});

it('fails to update task with invalid status', function () {
    $task = Task::factory()->create();

    $response = $this->putJson("/api/tasks/{$task->id}", [
        'title' => 'Updated title',
        'description' => 'Updated',
        'status' => 'invalid_status',
    ]);

    $response
        ->assertStatus(422)
        ->assertJsonValidationErrors(['status']);

    $this->assertDatabaseHas('tasks', [
        'id' => $task->id,
        'status' => $task->status,
    ]);
});

it('deletes a task, positive', function () {
    $task = Task::factory()->create();

    $response = $this->deleteJson("/api/tasks/{$task->id}");

    $response->assertStatus(200);

    $this->assertDatabaseMissing('tasks', [
        'id' => $task->id,
    ]);
});

it('deletes a task, negative', function () {
    $response = $this->deleteJson('/api/tasks/444');

    $response->assertStatus(404);
});
