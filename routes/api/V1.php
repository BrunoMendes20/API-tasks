<?php

use App\Http\Controllers\Api\v1\AuthController;
use App\Http\Controllers\Api\v1\TaskController;
use Illuminate\Support\Facades\Route;
use App\Services\ApiResponse;

// Health check da API
Route::get('/status', function () {
    return ApiResponse::success('API is running smoothly');
})->middleware('auth:sanctum');

// Task CRUD (core API feature)
Route::apiResource('tasks', TaskController::class)->middleware('auth:sanctum');

Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');
