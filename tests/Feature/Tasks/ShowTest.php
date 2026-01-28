<?php

use App\Models\Task;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;

uses(RefreshDatabase::class);

it('can show task', function () {
    $user = User::factory()->create();

    $task = Task::factory()->create([
        'user_id' => $user->id,
        'title' => 'Minha tarefa',
    ]);

    Sanctum::actingAs($user);
    $response = $this->getJson("api/V1/tasks/{$task->id}");

    $response->assertStatus(200)->assertJsonFragment([
        'id' => $task->id,
        'title' => 'Minha tarefa',
    ]);
});

it('cannot show another users task', function () {
    $userA = User::factory()->create();
    $userB = User::factory()->create();

    $task = Task::factory()->create([
        'user_id' => $userB->id,
    ]);

    Sanctum::actingAs($userA);

    $response = $this->getJson("api/V1/tasks/{$task->id}");

    $response->assertStatus(403);
});

it('cannot show task without authentication', function () {
    $user = User::factory()->create();

    $task = Task::factory()->create([
        'user_id' => $user->id,
    ]);

    $response = $this->getJson("/api/V1/tasks/{$task->id}");

    $response->assertStatus(401);
});

it('task does not exist', function () {
    $user = User::factory()->create();
    Sanctum::actingAs($user);

    $reponse = $this->getJson('/api/V1/tasks/9999999999999999');

    $reponse->assertStatus(404);
});
