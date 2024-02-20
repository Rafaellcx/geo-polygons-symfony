<?php

namespace App\Http\Helpers;

use Symfony\Component\HttpFoundation\JsonResponse;

class JsonFormat
{
    public static function success(string $message = 'OK', array $data = [], int $code = 200): JsonResponse
    {
        return new JsonResponse([
            "status" => "success",
            "message" => $message,
            "data" => $data,
        ], $code);
    }

    public static function error(string $message, array $data = [], int $code = 400): JsonResponse
    {
        return new JsonResponse([
            "status" => "failed",
            "message" => $message,
            "data" => $data,
        ], $code);
    }
}
