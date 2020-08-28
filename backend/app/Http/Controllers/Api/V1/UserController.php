<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\DeleteUserRequest;
use App\Http\Requests\Api\V1\GetUserRequest;
use App\Http\Requests\Api\V1\IndexUserRequest;
use App\Http\Requests\Api\V1\UpdateUserRequest;
use App\User;

class UserController extends Controller
{
    /**
     * @api {get} /user 1. User all users
     * @apiVersion 1.0.0
     * @apiGroup 2.User
     * @apiPermission admin, manager
     *
     * @apiHeader (Authorization Headers) {Bearer} Authorization Access token
     * @apiHeaderExample {json} Header-Example:
     *     {
     *       "Authorization": "Bearer yJ0eXAiOiJKV1QiLCJhbGciOiJSU..."
     *     }
     *
     * @apiSuccessExample {json} Success-Response:
     * 	HTTP/1.1 200 OK
     *	{
     *		"status": "OK",
     *		"result": [
     *			{
     *			    "id": 1,
     *			    "name": "User One",
     *			    "email": "user.one@test.com",
     *			    "role": "user"
     *			}
     *		]
     *	}
     *
     * @apiSuccess {String} status OK
     * @apiSuccess {Object} result Result data
     * @apiSuccess {String} result.id User ID
     * @apiSuccess {String} result.name User full name
     * @apiSuccess {String} result.email User email
     * @apiSuccess {String} result.role User role
     */

    /**
     * Get all users
     * @param IndexUserRequest $request
     * @return mixed
     */
    public function index(IndexUserRequest $request)
    {
        //TODO: pagination

        return response()->success(
            User::getAllUsers()
        );
    }

    /**
     * @api {get} /user/:id 2. Get user data
     * @apiVersion 1.0.0
     * @apiGroup 2.User
     * @apiPermission user, admin, manager
     *
     * @apiHeader (Authorization Headers) {Bearer} Authorization Access token
     * @apiHeaderExample {json} Header-Example:
     *     {
     *       "Authorization": "Bearer yJ0eXAiOiJKV1QiLCJhbGciOiJSU..."
     *     }
     *
     * @apiParam {String} id User ID or specified constant <code>me</code>.
     *
     * @apiSuccessExample {json} Success-Response:
     * 	HTTP/1.1 200 OK
     *	{
     *		"status": "OK",
     *		"result": {
     *			"name": "User One",
     *			"email": "user.one@test.com",
     *			"role": "user",
     *			"preferred_working_hour_per_day": 10
     *		}
     *	}
     *
     * @apiSuccess {String} status OK
     * @apiSuccess {Object} result Result data
     * @apiSuccess {String} result.name User full name
     * @apiSuccess {String} result.email User email
     * @apiSuccess {String} result.role User role
     * @apiSuccess {Integer} result.preferred_working_hour_per_day User setting *preferred working hour per day*
     */

    /**
     * Get the authenticated User
     * @param GetUserRequest $request
     * @return mixed
     */
    public function get(GetUserRequest $request)
    {
        $user = User::find($request->getTargetUserId());

        return response()->success([
            'name'  => $user->name,
            'email' => $user->email,
            'role'  => $user->getRoleNames()->first(),
            'preferred_working_hour_per_day'    => $user->getPreferredHours()
        ]);
    }

    /**
     * @api {put} /user/:id 4. Update user data
     * @apiVersion 1.0.0
     * @apiGroup 2.User
     * @apiPermission user, admin, manager
     *
     * @apiHeader (Authorization Headers) {Bearer} Authorization Access token
     * @apiHeaderExample {json} Header-Example:
     *     {
     *       "Authorization": "Bearer yJ0eXAiOiJKV1QiLCJhbGciOiJSU..."
     *     }
     *
     * @apiParam {String} id The User ID or specified constant <code>me</code>.
     * @apiParam {String} name Full name of the user.
     * @apiParam {String} [email] User email (only users with admin/manager roles can edit email)
     * @apiParam {String=user,manager,admin} [role] User role (only user with admin role can edit role)
     * @apiParam {String} preferred_working_hour_per_day User setting *preferred working hour per day*
     *
     * @apiSuccessExample {json} Success-Response:
     * 	HTTP/1.1 204 No Content
     */

    /**
     * Update existing user
     * @param UpdateUserRequest $request
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateUserRequest $request)
    {
        User::find($request->getTargetUserId())
            ->updateUser($request->validated(), $request->isNeedToUpdateEmail);

        return response()->noContent();
    }

    /**
     * @api {delete} /user/:id 5. Delete a user
     * @apiVersion 1.0.0
     * @apiGroup 2.User
     * @apiPermission admin, manager
     *
     * @apiHeader (Authorization Headers) {Bearer} Authorization Access token
     * @apiHeaderExample {json} Header-Example:
     *     {
     *       "Authorization": "Bearer yJ0eXAiOiJKV1QiLCJhbGciOiJSU..."
     *     }
     *
     * @apiParam {Integer} id The user ID.
     *
     * @apiSuccessExample {json} Success-Response:
     * 	HTTP/1.1 204 No Content
     */

    /**
     * Delete the user
     * @param DeleteUserRequest $request
     * @param User $user
     * @return \Illuminate\Http\Response
     * @throws \App\Exceptions\CannotBeExecutedException
     */
    public function delete(DeleteUserRequest $request, User $user)
    {
        $user->deleteUser();

        return response()->noContent();
    }
}
