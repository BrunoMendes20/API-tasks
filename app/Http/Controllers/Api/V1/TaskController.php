<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\Task\StoreTaskRequest;
use App\Http\Requests\Api\V1\Task\UpdateTaskRequest;
use App\Http\Resources\Api\V1\TaskResource;
use App\Models\Task;
use App\Services\ApiResponse;

class TaskController extends Controller
{
    /**
    * Display a listing of the resource.
    */
    public function index()
    {
        $this->authorize('viewAny', Task::class);

        $tasks = auth()->user()->tasks()->latest()->get();

        return ApiResponse::success(
            TaskResource::collection($tasks)
        );
    }

    public function store(StoreTaskRequest $request)
    {
        $task = auth()->user()->tasks()->create($request->validated());

        return ApiResponse::success(
            new TaskResource($task),
            'Tarefa criada com sucesso',
            201
        );
    }

    public function show(Task $task)
    {
        $this->authorize('view', $task);

        return ApiResponse::success(new TaskResource($task));
    }

    public function update(UpdateTaskRequest $request, Task $task)
    {
        $this->authorize('update', $task);

        $task->update($request->validated());

        return ApiResponse::success(new TaskResource($task));
    }

    public function destroy(Task $task)
    {
        $this->authorize('delete', $task);

        $task->delete();

        return ApiResponse::success('Tarefa deletada com sucesso');
    }
}
