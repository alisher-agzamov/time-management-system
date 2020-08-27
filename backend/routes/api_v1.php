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


Route::get('status', 'Api\V1\StatusController@index')->name('status.index');

Route::group(['prefix' => 'auth'], function () {
    Route::post('signup', 'Api\V1\AuthController@signup')->name('auth.signup');
    Route::post('login', 'Api\V1\AuthController@login')->name('auth.login');

    Route::group(['middleware' => 'auth:api'], function() {
        Route::get('logout', 'Api\V1\AuthController@logout')->name('auth.logout');
    });
});

Route::group(['middleware' => ['auth:api']], function() {
    Route::get('user', 'Api\V1\UserController@index');
    Route::get('user/{id}', 'Api\V1\UserController@get');
    Route::put('user/{id}', 'Api\V1\UserController@update'); // update by user ID or /me
    Route::delete('user/{user}', 'Api\V1\UserController@delete');

    Route::get('roles', 'Api\V1\RoleController@index')->name('roles.index');
    Route::post('user/create', 'Api\V1\AuthController@signup');
});

Route::group(['middleware' => ['auth:api', 'can:tasks']], function() {
    Route::get('tasks', 'Api\V1\TaskController@index')->name('tasks.index');
    Route::get('tasks/{task}', 'Api\V1\TaskController@get')->name('tasks.get');
    Route::get('tasks/{id}/export', 'Api\V1\TaskController@export')->name('tasks.export');
    Route::post('tasks', 'Api\V1\TaskController@store')->name('tasks.store');
    Route::put('tasks/{task}', 'Api\V1\TaskController@update')->name('tasks.update');
    Route::delete('tasks/{task}', 'Api\V1\TaskController@delete')->name('tasks.delete');
});


