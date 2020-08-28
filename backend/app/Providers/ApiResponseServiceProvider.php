<?php

namespace App\Providers;

use Illuminate\Support\Facades\Response;
use Illuminate\Support\ServiceProvider;

class ApiResponseServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        Response::macro('success', function ($data) {
            return Response::json([
                'status'    => 'OK',
                'result'    => $data
            ], 200);
        });

        Response::macro('created', function ($data = '') {
            return Response::json([
                'status'    => 'OK',
                'result'    => $data
            ], 201);
        });

        Response::macro('noContent', function ($data = '') {
            return Response::json([
                'status'    => 'OK',
                'result'    => $data
            ], 204);
        });

        Response::macro(

            'accessDenied', function ($data = 'exceptions.access_denied') {
            return Response::json([
                'status'    => 'ERROR',
                'error'   => __($data)
            ], 403);
        });

        Response::macro('notFound', function ($data = 'exceptions.not_found') {
            return Response::json([
                'status'    => 'ERROR',
                'error'   => __($data)
            ], 404);
        });

        Response::macro('cannotBeExecuted', function ($data = 'exceptions.cannot_be_executed') {
            return Response::json([
                'status'    => 'ERROR',
                'error'   => __($data)
            ], 500);
        });
    }
}
