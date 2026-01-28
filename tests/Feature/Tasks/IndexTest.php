<?php

use App\Models\Task;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;

uses(RefreshDatabase::class);

it('can list all tasks', function () {
    $user = User::factory()->create();

    Task::factory()->count(3)->create([
        'user_id' => $user->id,
    ]);

    Sanctum::actingAs($user);
    $response = $this->getJson('api/V1/tasks');

    $response->assertStatus(200)->assertJsonCount(3, 'data');
});

it('cannot list tasks without authentication', function () {
    $response = $this->getJson('/api/V1/tasks');

    $response->assertStatus(401);
});

it('list only tasks from authenticated user', function () {
    $userA = User::factory()->create();
    $userB = User::factory()->create();

    Task::factory()->count(2)->create([
        'user_id' => $userA->id,
    ]);

    Task::factory()->count(3)->create([
        'user_id' => $userB->id,
    ]);

    Sanctum::actingAs($userA);

    $response = $this->getJson('api/V1/tasks');

    $response->assertStatus(200)->assertJsonCount(2, 'data');
});
