<?php

namespace App\Exceptions;

use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        'password',
        'password_confirmation',
    ];

    /**
     * Report or log an exception.
     *
     * @param  \Throwable  $exception
     * @return void
     *
     * @throws \Exception
     */
    public function report(Throwable $exception)
    {
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Throwable  $exception
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @throws \Throwable
     */
    public function render($request, Throwable $exception)
    {
        if ($exception instanceof ModelNotFoundException) {
            return response()->notFound();
        }
        elseif ($exception instanceof UserNotFoundException) {
            return response()->notFound();
        }
        elseif ($exception instanceof AccessDeniedException) {
            return response()->accessDenied();
        }
        elseif ($exception instanceof CannotBeExecutedException) {
            return response()->cannotBeExecuted();
        }
        elseif ($exception instanceof AuthenticationException) {
            return response()->accessDenied();
        }
        elseif ($exception instanceof \Illuminate\Auth\Access\AuthorizationException) {
            return response()->accessDenied();
        }
        elseif ($exception instanceof AccessDeniedHttpException) {
            return response()->accessDenied();
        }

        return parent::render($request, $exception);
    }
}