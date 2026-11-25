
<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\AuthController;
use App\Http\Controllers\Api\V1\BusinessController;
use App\Http\Controllers\Api\V1\BusinessProfileController;
use App\Http\Controllers\Api\V1\SocialOAuthController;
use App\Http\Controllers\Api\V1\SocialAccountController;



Route::prefix('v1')->group(function () {

    // Public authentication APIs
    Route::prefix('auth')->group(function () {
        Route::post('/register', [AuthController::class, 'register']);
        Route::post('/login', [AuthController::class, 'login']);
    });
    // Provider redirects here after authorization.
    Route::get(
        'social/{provider}/callback',
        [SocialOAuthController::class, 'callback']
    );

    // Protected APIs
    Route::middleware('auth:sanctum')->group(function () {
        // Authentication
        Route::post('/auth/logout', [AuthController::class, 'logout']);

        // Business management
        Route::apiResource('businesses', BusinessController::class)
            ->only(['index', 'store', 'show', 'update']);

        // Business profile
        Route::get(
            'businesses/{business}/profile',
            [BusinessProfileController::class, 'show']
        );

        Route::put(
            'businesses/{business}/profile',
            [BusinessProfileController::class, 'update']
        );

         Route::get(
            'businesses/{business}/social-accounts',
            [SocialAccountController::class, 'index']
        );

        Route::get(
            'social/{provider}/connect',
            [SocialOAuthController::class, 'connect']
        );

    });
});
