<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Services\Auth\AuthService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Http\Requests\Api\V1\RegisterRequest;
use App\Http\Requests\Api\V1\LoginRequest;
use App\Helpers\ApiResponse;


class AuthController extends ApiController
{
    protected $authService;

    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    public function register(RegisterRequest $request)
    {
        $result = $this->authService->register(
            $request->validated()
        );

        return $this->success(
            $result,
            'Registration successful',
            201
        );
    }


    public function login(LoginRequest $request)
    {
        $result = $this->authService->login(
            $request->validated()
        );

         return $this->success(
            $result,
            'Login successful'
        );
    }

    public function me(Request $request)
    {
        return $this->success(
            $request->user(),
            'User details retrieved successfully'
        );
    }

    public function logout(Request $request)
    {
        $this->authService->logout($request->user());

        return $this->success(
            null,
            'Logout successful'
        );
    }
}