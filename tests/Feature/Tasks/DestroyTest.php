<?php

use App\Models\Task;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;

uses(RefreshDatabase::class);

it('can delete a task', function () {
    $user = User::factory()->create();

    $task = Task::factory()->create([
        'user_id' => $user->id,
    ]);

    Sanctum::actingAs($user);

    $response = $this->deleteJson("api/V1/tasks/{$task->id}");

    $response->assertStatus(204);

    $this->assertSoftDeleted('tasks', [
        'id' => $task->id,
    ]);
});

it('cannot delete another users task', function () {
    $userA = User::factory()->create();
    $userB = User::factory()->create();

    $task = Task::factory()->create([
        'user_id' => $userB->id,
    ]);

    Sanctum::actingAs($userA);

    $reponse = $this->deleteJson("api/V1/tasks/{$task->id}");

    $reponse->assertStatus(403);

    $this->assertDatabaseHas('tasks', [
        'id' => $task->id,
        'user_id' => $userB->id,
    ]);
});

it('cannot delete task without authentication', function () {
    $user = User::factory()->create();

    $task = Task::factory()->create([
        'user_id' => $user->id,
    ]);

    $response = $this->deleteJson("/api/V1/tasks/{$task->id}", [
        'title' => 'Novo título',
    ]);

    $response->assertStatus(401);
});

it('returns 404 when delete non existing task', function () {
    $user = User::factory()->create();
    Sanctum::actingAs($user);

    $response = $this->deleteJson('api/V1/tasks/99999999');
    $response->assertStatus(404);
});
