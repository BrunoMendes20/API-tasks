<?php

use App\Models\Task;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;

uses(RefreshDatabase::class);

it('can update a task', function () {
    $user = User::factory()->create();

    $task = Task::factory()->create([
        'user_id' => $user->id,
        'title' => 'Título antigo',
    ]);

    Sanctum::actingAs($user);

    $payload = [
        'title' => 'Título atualizado',
        'description' => 'Nova descrição',
    ];

    $response = $this->putJson("api/V1/tasks/{$task->id}", $payload);


    $response->assertStatus(200)->assertJsonFragment([
        'title' => 'Título atualizado',
    ]);

    $this->assertDatabaseHas('tasks', [
        'id' => $task->id,
        'title' => 'Título atualizado',
    ]);
});

it('cannot update another users task', function () {
    $userA = User::factory()->create();
    $userB = User::factory()->create();

    $task = Task::factory()->create([
        'user_id' => $userB->id,
    ]);

    Sanctum::actingAs($userA);

    $reponse = $this->putJson("api/V1/tasks/{$task->id}", [
        'title' => 'Tentativa de invasão',

    ]);

    $reponse->assertStatus(403);
});

it('cannot update task without authentication', function () {
    $user = User::factory()->create();

    $task = Task::factory()->create([
        'user_id' => $user->id,
    ]);

    $response = $this->putJson("/api/V1/tasks/{$task->id}", [
        'title' => 'Novo título',
    ]);

    $response->assertStatus(401);
});

it('cannot update task with invalid data', function () {
    $user = User::factory()->create();
    Sanctum::actingAs($user);

    $task = Task::factory()->create([
        'user_id' => $user->id,
    ]);

    $reponse = $this->putJson("api/V1/tasks/{$task->id}", [
        'title' => '',

    ]);

    $reponse->assertStatus(422)->assertJsonValidationErrors(['title']);
});

it('returns 404 when updating non existing task', function () {
    $user = User::factory()->create();
    Sanctum::actingAs($user);

    $response = $this->putJson('api/V1/tasks/99999999', [
        'title' => 'Qualquer coisa',

    ]);

    $response->assertStatus(404);
});
