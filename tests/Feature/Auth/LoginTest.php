<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('can login with valid credentials', function () {
    $password = 'AaBb12345678';

    $user = User::factory()->create([
        'password' => bcrypt($password),
    ]);

    $response = $this->postJson('/api/V1/login', [
        'email' => $user->email,
        'password' => $password,
    ]);

    $response->assertStatus(200)->assertJsonStructure([
        'success',
        'message',
        'data' => [
            'token',
            'user' => [
                'id',
                'name',
                'email',
                'created_at',
            ],
        ],
    ]);
});


it('cannot login with invalid password', function () {
    $user = User::factory()->create([
        'password' => bcrypt('12345678'),

    ]);

    $response = $this->postJson('/api/V1/login', [
        'email' => $user->email,
        'password' => '564564564564',
    ]);
    $response->assertStatus(401);
});

it('cannot login with non existing email', function () {
    $response = $this->postJson('/api/V1/login', [
        'email' => 'inexistente@test.com',
        'password' => '12345678',
    ]);

    $response->assertStatus(401);
});

it('cannot login without required fields', function () {
    $response = $this->postJson('/api/V1/login', []);

    $response->assertStatus(422)->assertJsonValidationErrors(['email', 'password']);
});
