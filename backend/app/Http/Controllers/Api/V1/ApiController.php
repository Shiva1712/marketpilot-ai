<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Helpers\ApiResponse;

class ApiController extends Controller
{
    protected function success(
        $data = null,
        string $message = 'Success',
        int $code = 200
    ) {
        return ApiResponse::success($data, $message, $code);
    }

    protected function error(
        string $message = 'Something went wrong',
        $errors = null,
        int $code = 400
    ) {
        return ApiResponse::error($message, $errors, $code);
    }
}
