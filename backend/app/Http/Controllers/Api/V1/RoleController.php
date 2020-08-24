<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\RoleIndexRequest;
use Spatie\Permission\Models\Role;

class RoleController extends Controller
{
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
