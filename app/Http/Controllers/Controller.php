<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\JsonResponse;

abstract class Controller
{
    use AuthorizesRequests, ValidatesRequests;

    protected function response($status, $data = null, $message = null, $log = null, $httpStatusCode = 200): JsonResponse
    {
        return response()->json([
            "status" => $status,
            "data" => $data,
            "message" => $message,
        ], $httpStatusCode ?? 500, [], JSON_UNESCAPED_UNICODE);
    }
}
