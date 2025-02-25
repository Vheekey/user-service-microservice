<?php

namespace App\Infrastructure\Middleware;

use App\Shared\Response;
use Closure;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\ResponseFactory;
use Symfony\Component\HttpFoundation\Response as SymfonyResponse;

class ValidateApiKey
{
    public function handle(Request $request, Closure $next): JsonResponse|Response|ResponseFactory|\Illuminate\Http\Response
    {
        $apiKey = $request->header('X-API-KEY');

        if (!$apiKey || $apiKey !== config('app.api_key')) {
            return Response::render(SymfonyResponse::HTTP_UNAUTHORIZED, 'Invalid API KEY', [], $request->isJson());
        }

        return $next($request);
    }
}
