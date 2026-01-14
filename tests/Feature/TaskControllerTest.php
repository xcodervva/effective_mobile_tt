<?php

use App\Models\Task;

it('returns list of tasks', function () {
    Task::factory()->count(6)->create();

    $response = $this->getJson('/api/tasks');

    $response
        ->assertStatus(200)
        ->assertJsonCount(6);
});
