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
     * Get all users
     * @param IndexUserRequest $request
     * @return mixed
     */
    public function index(IndexUserRequest $request)
    {
        //TODO: pagination

        $users = User::orderBy('id', 'DESC')
            ->get()
            ->toArray();

        return response()->success($users);
    }

    /**
     * Get the authenticated User
     * @param GetUserRequest $request
     * @return mixed
     */
    public function get(GetUserRequest $request)
    {
        $user = User::find($request->getTargetUserId());

        return response()->success($user);
    }

    /**
     * Update existing user
     * @param UpdateUserRequest $request
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateUserRequest $request)
    {
        User::find($request->getTargetUserId())
            ->updateUser($request->validated());

        return response()->noContent();
    }

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
