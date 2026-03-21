<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;

uses(RefreshDatabase::class);

it('can create task', function () {
    $user = User::factory()->create();

    Sanctum::actingAs($user);

    $payload = [
        'title' => 'Nova tarefa',
        'description' => 'Descrição de teste',
    ];

    $response = $this->postJson('api/V1/tasks', $payload);

    $response->assertStatus(201)->assertJsonFragment([
        'title' => 'Nova tarefa',
    ]);

    $this->assertDatabaseHas('tasks', [
        'title' => 'Nova tarefa',
        'user_id' => $user->id,
    ]);
});


it('cannot create task without authentication', function () {
    $payload = [
        'title' => 'Nova tarefa',
    ];

    $response = $this->postJson('/api/V1/tasks', $payload);

    $response->assertStatus(401);
});

it('cannot create task without title', function () {
    $userA = User::factory()->create();

    Sanctum::actingAs($userA);

    $payload = [
        'description' => 'Sem título',
    ];

    $response = $this->postJson('api/V1/tasks', $payload);

    $response->assertStatus(422)->assertJsonValidationErrors(['title']);
});


it('always assigns task to authenticated user ignoring user_id in payload', function () {
    $user = User::factory()->create();
    Sanctum::actingAs($user);

    $payload = [
        'title' => 'Minha tarefa',
        'user_id' => 999,
    ];

    $reponse = $this->postJson('/api/V1/tasks', $payload);

    $reponse->assertStatus(201);

    $this->assertDatabaseHas('tasks', [
        'title' => 'Minha tarefa',
        'user_id' => $user->id,
    ]);
});
