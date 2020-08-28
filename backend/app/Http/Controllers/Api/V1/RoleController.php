<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\RoleIndexRequest;
use Spatie\Permission\Models\Role;

class RoleController extends Controller
{
    /**
     * @api {get} /roles 1. User all roles
     * @apiVersion 1.0.0
     * @apiGroup 4.Role
     * @apiPermission admin
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
     *			    "name": "user"
     *			},
     *			{
     *			    "id": 2,
     *			    "name": "manager"
     *			}
     *          ...
     *		]
     *	}
     *
     * @apiSuccess {String} status OK
     * @apiSuccess {Object} result Result data
     * @apiSuccess {String} result.id Role ID
     * @apiSuccess {String} result.name Role name
     */

    /**
     * Get all roles
     * @param RoleIndexRequest $request
     * @return mixed
     */
    public function index(RoleIndexRequest $request)
    {
        return response()->success(
            Role::all(['id', 'name'])->toArray()
        );
    }
}
