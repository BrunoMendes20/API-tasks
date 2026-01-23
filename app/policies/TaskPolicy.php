<?php

namespace App\Policies;

use App\Models\Task;
use App\Models\User;

class TaskPolicy
{
    /**
     * Determine whether the user can view any tasks.
     * Used in index()
     */
    public function viewAny(User $user): bool
    {
        // Any authenticated user can list their own tasks
        return true;
    }

    /**
     * Determine whether the user can view the task.
     * Used in show()
     */
    public function view(User $user, Task $task): bool
    {
        // Can only view if the task belongs to the user
        return $task->user_id === $user->id;
    }

    /**
     * Determine whether the user can create tasks.
     * Used in store()
     */
    public function create(User $user): bool
    {
        // Any authenticated user can create tasks
        return true;
    }

    /**
     * Determine whether the user can update the task.
     * Used in update()
     */
    public function update(User $user, Task $task): bool
    {
        // Can only update if the user owns the task
        return $task->user_id === $user->id;
    }

    /**
     * Determine whether the user can delete the task.
     * Used in destroy()
     */
    public function delete(User $user, Task $task): bool
    {
        // Can only delete if the user owns the task
        return $task->user_id === $user->id;
    }
}
