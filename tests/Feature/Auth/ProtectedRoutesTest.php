<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;

uses(RefreshDatabase::class);

it('cannot access protected route without token', function () {
    $response = $this->getJson('/api/V1/tasks');

    $response->assertStatus(401);
});

it('can access protected route with valid token', function () {
    $user = User::factory()->create();

    Sanctum::actingAs($user);

    $response = $this->getJson('/api/V1/tasks');

    $response->assertStatus(200);
});
