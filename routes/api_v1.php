<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/



Route::group(['prefix' => 'auth'], function () {
    Route::post('signup', 'Api\V1\AuthController@signup');
    Route::post('login', 'Api\V1\AuthController@login');

    Route::group(['middleware' => 'auth:api'], function() {
        Route::get('logout', 'Api\V1\AuthController@logout');
    });
});

Route::group(['middleware' => ['auth:api']], function() {
    Route::get('user', 'Api\V1\UserController@index');
    Route::get('user/{id}', 'Api\V1\UserController@get');
    Route::put('user/{id}', 'Api\V1\UserController@update'); // update by user ID or /me
    Route::delete('user/{user}', 'Api\V1\UserController@delete');
});

Route::group(['middleware' => ['auth:api', 'can:tasks']], function() {
    Route::get('tasks', 'Api\V1\TaskController@index');
    Route::get('tasks/{task}', 'Api\V1\TaskController@get');
    Route::post('tasks', 'Api\V1\TaskController@store');
    Route::put('tasks/{task}', 'Api\V1\TaskController@update');
    Route::delete('tasks/{task}', 'Api\V1\TaskController@delete');
});


