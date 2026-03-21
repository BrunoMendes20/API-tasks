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

    /**
    * Deletes a task (soft delete).
    *
    * Note: This method intentionally does not use $this->authorize() or ApiResponse.
    *
    * During development, using $this->authorize('delete', $task) caused an unexpected
    * exception instead of returning 403, which broke the test assertion (assertStatus(403)).
    * The manual check was adopted as a pragmatic solution to keep the test aligned
    * with the expected behavior.
    *
    * ApiResponse::success() was also replaced by response()->noContent() to correctly
    * return HTTP 204, which is the expected status for delete operations with no body.
    */
    public function destroy(Task $task)
    {
        if ($task->user_id !== auth()->id()) {
            return response()->json(['message' => 'Forbidden'], 403);
        }

        $task->delete();

        return response()->noContent();
    }
}
