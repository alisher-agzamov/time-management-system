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
     * @api {post} /auth/signup 1. Sign up a new user
     * @apiVersion 1.0.0
     * @apiGroup 1.Auth
     * @apiPermission unauthorized
     *
     * @apiParam {String} name User full name
     * @apiParam {String} email User email
     * @apiParam {String} password User password
     * @apiParam {String} password_confirmation Password confirmation
     * @apiParam {String} preferred_working_hour_per_day User setting *preferred working hour per day*
     *
     * @apiSuccessExample {json} Success-Response:
     * 	HTTP/1.1 201 Created
     */

    /**
     * @api {post} /user 3. Create a new user
     * @apiVersion 1.0.0
     * @apiGroup 2.User
     * @apiPermission admin,manager
     *
     * @apiHeader (Authorization Headers) {Bearer} Authorization Access token
     * @apiHeaderExample {json} Header-Example:
     *     {
     *       "Authorization": "Bearer yJ0eXAiOiJKV1QiLCJhbGciOiJSU..."
     *     }
     *
     *
     * @apiParam {String} name User full name
     * @apiParam {String} email User email
     * @apiParam {String} password User password
     * @apiParam {String} password_confirmation Password confirmation
     * @apiParam {String} preferred_working_hour_per_day User setting *preferred working hour per day*
     * @apiParam {String=user,manager,admin} [role] User role (only user with admin role can set a user role)
     *
     * @apiSuccessExample {json} Success-Response:
     * 	HTTP/1.1 201 Created
     */

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
     * @api {post} /auth/login 2. Login user
     * @apiVersion 1.0.0
     * @apiGroup 1.Auth
     * @apiPermission unauthorized
     *
     * @apiParam {String} email User email
     * @apiParam {String} password User password
     *
     * @apiSuccessExample {json} Success-Response:
     * 	HTTP/1.1 200 OK
     *	{
     *		"status": "OK",
     *		"result": {
     *			"access_token": "yJ0eXAiOiJKV1QiLCJhbGciOiJSU...",
     *			"token_type": "Bearer",
     *			"expires_at": "2021-08-28 16:29:41"
     *		}
     *	}
     *
     * @apiSuccess {String} status OK
     * @apiSuccess {Object} result Result data
     * @apiSuccess {String} result.access_token User access token
     * @apiSuccess {String} result.token_type Token type
     * @apiSuccess {String} result.expires_at Token expiration date
     */

    /**
     * Login user and generate a new token
     * @param LoginAuthRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(LoginAuthRequest $request)
    {
        $credentials = request(['email', 'password']);

        if(!Auth::attempt($credentials)) {
            return response()->accessDenied('exceptions.incorrect_email_or_password');
        }

        $token = $request->user()
            ->createToken(__('Personal Access Token'));

        return response()->success([
            'access_token'  => $token->accessToken,
            'token_type'    => 'Bearer',
            'expires_at'    => Carbon::parse($token->token->expires_at)->toDateTimeString()
        ]);

    }

    /**
     * @api {get} /auth/login 3. Logout
     * @apiVersion 1.0.0
     * @apiGroup 1.Auth
     * @apiPermission user,manager,admin
     * @apiHeader (Authorization Headers) {Bearer} Authorization Access token
     * @apiHeaderExample {json} Header-Example:
     *     {
     *       "Authorization": "Bearer yJ0eXAiOiJKV1QiLCJhbGciOiJSU..."
     *     }
     *
     * @apiSuccessExample {json} Success-Response:
     * HTTP/1.1 204 No Content
     */

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
