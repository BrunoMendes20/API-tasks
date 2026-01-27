<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('can logout authenticated user', function () {
    $user = User::factory()->create();

    $token = $user->createToken('test')->plainTextToken;

    $response = $this->withHeader('Authorization', "Bearer {$token}")
                    ->postJson('/api/V1/logout');

    $response->assertStatus(204);
});

it('cannot access protected route after logout', function () {
    $user = User::factory()->create();

    $token = $user->createToken('test')->plainTextToken;

    // Accesses a protected route with a valid token.
    $this->withHeader('Authorization', "Bearer {$token}")
        ->getJson('/api/V1/tasks')
        ->assertStatus(200);

    // Log out using the same token
    $this->withHeader('Authorization', "Bearer {$token}")
        ->postJson('/api/V1/logout')
        ->assertStatus(204);

    // helper
    resetAuthCache();

    // Try accessing again with the same token.
    $this->withHeader('Authorization', "Bearer {$token}")
        ->getJson('/api/V1/tasks')
        ->assertStatus(401);
});

it('cannot logout without authentication', function () {
    $response = $this->postJson('/api/V1/logout');

    $response->assertStatus(401);
});
