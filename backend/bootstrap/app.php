
<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Auth\Access\AuthorizationException;

use App\Helpers\ApiResponse;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->redirectGuestsTo('/login');
    })
    ->withExceptions(function (Exceptions $exceptions) {

        // Validation Exception - 422
        $exceptions->render(function (
            ValidationException $e,
            Request $request
        ) {
            if (!$request->is('api/*')) {
                return null;
            }

            return ApiResponse::error(
                'Validation failed',
                $e->errors(),
                422
            );
        });

        // Authentication Exception - 401
        $exceptions->render(function (
            AuthenticationException $e,
            Request $request
        ) {
            if (!$request->is('api/*')) {
                return null;
            }

            return ApiResponse::error(
                'Unauthenticated',
                null,
                401
            );
        });

        // Model Not Found - 404
        $exceptions->render(function (
            ModelNotFoundException $e,
            Request $request
        ) {
            if (!$request->is('api/*')) {
                return null;
            }

            return ApiResponse::error(
                'Resource not found',
                null,
                404
            );
        });

        // Authorization Exception - 403
        $exceptions->render(function (
            AuthorizationException $e,
            Request $request
        ) {
            if (!$request->is('api/*')) {
                return null;
            }

            return ApiResponse::error(
                'You are not authorized to perform this action',
                null,
                403
            );
        });

    })->create();
