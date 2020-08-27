<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class StatusController extends Controller
{
    /**
     * API status check
     * @return mixed
     */
    public function index()
    {
        return response()->success([]);
    }
}
