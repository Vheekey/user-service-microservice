<?php

namespace App\Shared;

use Illuminate\Http\JsonResponse;
use Illuminate\Routing\ResponseFactory;

class Response
{
    public static function render(int $statusCode, string $message, array $data, bool $json = true): JsonResponse|ResponseFactory
    {
        $responseArray = [
            'status' => $statusCode,
            'message' => $message,
            'data' => $data
        ];

        return $json
            ? response()->json($responseArray, $statusCode)
            : response($responseArray, $statusCode)
                ->header('Content-Type', 'application/xml');
    }
}
