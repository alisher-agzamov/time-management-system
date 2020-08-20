<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\DeleteTaskRequest;
use App\Http\Requests\Api\V1\GetTaskRequest;
use App\Http\Requests\Api\V1\IndexTaskRequest;
use App\Http\Requests\Api\V1\StoreTaskRequest;
use App\Http\Requests\Api\V1\UpdateTaskRequest;
use App\Task;
use App\User;

class TaskController extends Controller
{
    /**
     * Get all user tasks
     * @param IndexTaskRequest $request
     * @return mixed
     */
    public function index(IndexTaskRequest $request)
    {
        $user = User::find($request->getTargetUserId());

        //TODO: pagination
        $tasks = $user->tasks()
            ->orderBy('date', 'DESC')
            ->get()
            ->toArray();

        return response()->success($tasks);
    }

    /**
     * Get the task
     */
    public function get(GetTaskRequest $request, Task $task)
    {
        return response()->success($task);
    }

    /**
     * Create a new task
     * @param StoreTaskRequest $request
     * @return mixed
     * @throws \App\Exceptions\AccessDeniedException
     * @throws \App\Exceptions\CannotBeExecutedException
     */
    public function store(StoreTaskRequest $request)
    {
        $task = new Task;
        $task->createTask($request->validated());

        return response()->created($task);
    }

    /**
     * Update existing task
     * @param UpdateTaskRequest $request
     * @param Task $task
     * @return \Illuminate\Http\Response
     * @throws \App\Exceptions\CannotBeExecutedException
     */
    public function update(UpdateTaskRequest $request, Task $task)
    {
        $task->updateTask($request->validated());

        return response()->noContent();
    }

    /**
     * Delete the task
     * @param DeleteTaskRequest $request
     * @param Task $task
     * @return \Illuminate\Http\Response
     * @throws \Exception
     */
    public function delete(DeleteTaskRequest $request, Task $task)
    {
        if(!$task->delete()) {
            return response()->cannotBeExecuted();
        }

        return response()->noContent();
    }
}

