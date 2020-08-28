<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class StatusController extends Controller
{
    /**
     * @api {get} /status 1. API Status check
     * @apiVersion 1.0.0
     * @apiGroup 5.Common
     * @apiPermission unauthorized
     *
     * @apiSuccessExample {json} Success-Response:
     * 	HTTP/1.1 200 OK
     *	{
     *		"status": "OK",
     *		"result": []
     *	}
     *
     * @apiSuccess {String} status OK
     * @apiSuccess {Array} result Result data
     */

    /**
     * API status check
     * @return mixed
     */
    public function index()
    {
        return response()->success([]);
    }
}
