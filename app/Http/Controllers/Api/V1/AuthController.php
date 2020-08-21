<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\LoginAuthRequest;
use App\Http\Requests\Api\V1\SignupAuthRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\User;

class AuthController extends Controller
{
    /**
     * Create a new user
     * @param SignupAuthRequest $request
     * @return mixed
     * @throws \App\Exceptions\CannotBeExecutedException
     */
    public function signup(SignupAuthRequest $request)
    {
        $user = new User;
        $user->createNewUser($request->validated(), 'user');

        return response()->created();
    }

    /**
     * Login user and generate a new token
     * @param LoginAuthRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(LoginAuthRequest $request)
    {
        $credentials = request(['email', 'password']);

        if(!Auth::attempt($credentials)) {
            return response()->accessDenied();
        }

        $token = $request->user()
            ->createToken(__('Personal Access Token'));

        return response()->success([
            'access_token'  => $token->accessToken,
            'token_type'    => 'Bearer',
            'expires_at'    => Carbon::parse($token->token->expires_at)->toDateTimeString(),
            'user_role'     => $request->user()->getRoleNames()->first()
        ]);

    }

    /**
     * Logout user (Revoke the token)
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function logout(Request $request)
    {
        $request->user()
            ->token()
            ->revoke();

        return response()->noContent();
    }
}
