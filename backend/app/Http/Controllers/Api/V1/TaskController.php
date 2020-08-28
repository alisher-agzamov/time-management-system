<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\DeleteTaskRequest;
use App\Http\Requests\Api\V1\ExportTaskRequest;
use App\Http\Requests\Api\V1\GetTaskRequest;
use App\Http\Requests\Api\V1\IndexTaskRequest;
use App\Http\Requests\Api\V1\StoreTaskRequest;
use App\Http\Requests\Api\V1\UpdateTaskRequest;
use App\Task;
use App\User;

class TaskController extends Controller
{
    /**
     * @api {get} /tasks 1. Get user tasks
     * @apiVersion 1.0.0
     * @apiGroup 3.Task
     * @apiPermission user,admin
     *
     * @apiHeader (Authorization Headers) {Bearer} Authorization Access token
     * @apiHeaderExample {json} Header-Example:
     *     {
     *       "Authorization": "Bearer yJ0eXAiOiJKV1QiLCJhbGciOiJSU..."
     *     }
     *
     * @apiParam {String} date_from Filter: date from
     * @apiParam {String} date_to Filter: date to
     * @apiParam {String} [user_id] Get all tasks using user ID (only user with admin role can use the param)
     *
     * @apiSuccessExample {json} Success-Response:
     * HTTP/1.1 200 OK
     * {
     *      "status": "OK",
     *      "result": {
     *          "tasks": {
     *              "2020-08-26": {
     *                  "tasks": [
     *                      {
     *                          "id": 1,
     *                          "title": "Task title",
     *                          "description": "Task description",
     *                          "date": "2020-08-26",
     *                          "duration": 60
     *                      }
     *                  ],
     *                  "total_duration": 60,
     *                  "covered_day_hours": true
     *              }
     *          }
     *          "total_duration": 120
     *      }
     * }
     *
     * @apiSuccess {String} status OK
     * @apiSuccess {Object} result Result data
     * @apiSuccess {Array} result.tasks Tasks array indexed by date
     * @apiSuccess {Object} result.tasks.date Tasks group
     * @apiSuccess {String} result.tasks.date.tasks.id Task ID
     * @apiSuccess {String} result.tasks.date.tasks.title Task title
     * @apiSuccess {String} result.tasks.date.tasks.description Task description
     * @apiSuccess {String} result.tasks.date.tasks.date Date of the task
     * @apiSuccess {Integer} result.tasks.date.tasks.duration Duration of the task
     * @apiSuccess {Integer} result.tasks.date.total_duration Total duration of group of tasks
     * @apiSuccess {Integer} result.tasks.date.covered_day_hours Is date tasks duration covered preferred working hours
     * @apiSuccess {Integer} result.total_duration Total duration of all tasks
     */

    /**
     * Get all user tasks
     * @param IndexTaskRequest $request
     * @return mixed
     */
    public function index(IndexTaskRequest $request)
    {
        $user = User::find($request->getTargetUserId());

        //TODO: pagination

        return response()->success(
            Task::getGroupedUserTasks(
                $user,
                $request->get('date_from'),
                $request->get('date_to')
            )
        );
    }

    /**
     * @api {get} /tasks/:id 2. Get the task
     * @apiVersion 1.0.0
     * @apiGroup 3.Task
     * @apiPermission user,admin
     *
     * @apiHeader (Authorization Headers) {Bearer} Authorization Access token
     * @apiHeaderExample {json} Header-Example:
     *     {
     *       "Authorization": "Bearer yJ0eXAiOiJKV1QiLCJhbGciOiJSU..."
     *     }
     *
     * @apiParam {String} id Task ID
     *
     * @apiSuccessExample {json} Success-Response:
     * 	HTTP/1.1 200 OK
     *	{
     *		"status": "OK",
     *		"result": {
     *			"id": 1,
     *			"title": "Task title",
     *			"description": "Task description",
     *			"date": "2020-08-26",
     *			"duration": 60
     *		}
     *	}
     *
     * @apiSuccess {String} status OK
     * @apiSuccess {Object} result Result data
     * @apiSuccess {String} result.title Task title
     * @apiSuccess {String} result.description Task description
     * @apiSuccess {String} result.date Date of the task
     * @apiSuccess {Integer} result.duration Duration of the task
     */

    /**
     * Get the task
     */
    public function get(GetTaskRequest $request, Task $task)
    {
        return response()->success($task);
    }

    /**
     * @api {post} /tasks 3. Create a new task
     * @apiVersion 1.0.0
     * @apiGroup 3.Task
     * @apiPermission user, admin
     *
     * @apiHeader (Authorization Headers) {Bearer} Authorization Access token
     * @apiHeaderExample {json} Header-Example:
     *     {
     *       "Authorization": "Bearer yJ0eXAiOiJKV1QiLCJhbGciOiJSU..."
     *     }
     *
     * @apiParam {String} title Task title
     * @apiParam {String} description Task description
     * @apiParam {String} date Date
     * @apiParam {Integer} duration Duration of the task
     * @apiParam {Integer} [user_id] User ID (only user with admin role can use the param to create a task for a user)
     *
     * @apiSuccessExample {json} Success-Response:
     * 	HTTP/1.1 201 Created
     */

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
     * @api {put} /tasks/:id 4. Update a task
     * @apiVersion 1.0.0
     * @apiGroup 3.Task
     * @apiPermission user, admin
     *
     * @apiHeader (Authorization Headers) {Bearer} Authorization Access token
     * @apiHeaderExample {json} Header-Example:
     *     {
     *       "Authorization": "Bearer yJ0eXAiOiJKV1QiLCJhbGciOiJSU..."
     *     }
     *
     * @apiParam {String} title Task title
     * @apiParam {String} description Task description
     * @apiParam {String} date Date
     * @apiParam {Integer} duration Duration of the task
     *
     * @apiSuccessExample {json} Success-Response:
     * 	HTTP/1.1 204 No Content
     */

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
     * @api {delete} /tasks/:id 5. Delete a task
     * @apiVersion 1.0.0
     * @apiGroup 3.Task
     * @apiPermission admin, manager
     *
     * @apiHeader (Authorization Headers) {Bearer} Authorization Access token
     * @apiHeaderExample {json} Header-Example:
     *     {
     *       "Authorization": "Bearer yJ0eXAiOiJKV1QiLCJhbGciOiJSU..."
     *     }
     *
     * @apiParam {Integer} id The task ID.
     *
     * @apiSuccessExample {json} Success-Response:
     * 	HTTP/1.1 204 No Content
     */

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

    /**
     * @api {get} /tasks/export 6. Export user tasks
     * @apiVersion 1.0.0
     * @apiGroup 3.Task
     * @apiPermission user, admin
     *
     * @apiHeader (Authorization Headers) {Bearer} Authorization Access token
     * @apiHeaderExample {json} Header-Example:
     *     {
     *       "Authorization": "Bearer yJ0eXAiOiJKV1QiLCJhbGciOiJSU..."
     *     }
     *
     * @apiParam {String} date_from Filter: date from
     * @apiParam {String} date_to Filter: date to
     * @apiParam {String} [user_id] Get all tasks using user ID (only user with admin role can use the param)
     *
     * @apiSuccessExample {html} Success-Response:
     * HTTP/1.1 200 OK
     *
     * The server will generate HTML file
     */

    /**
     * Export filtered tasks
     * @param ExportTaskRequest $request
     * @return mixed
     */
    public function export(ExportTaskRequest $request)
    {
        $user = User::find($request->getTargetUserId());

        $tasks = Task::getGroupedUserTasks(
            $user,
            $request->get('date_from'),
            $request->get('date_to')
        );

        $dataRange = implode(' - ', $request->only(['date_from', 'date_to']));

        return response()->streamDownload(function () use ($tasks, $dataRange)  {
            echo view('export', [
                'tasks'     => $tasks,
                'dataRange' => $dataRange
            ]);
        }, 'export.html');
    }
}

