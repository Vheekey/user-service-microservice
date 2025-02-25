<?php

use App\Console\Commands\InitialiseDB;
use App\Infrastructure\Middleware\ValidateApiKey;
use App\Shared\Response;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpFoundation\Response as HttpResponse;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        api: __DIR__ . '/../routes/api.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->append([
            ValidateApiKey::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        $exceptions->render(function (ValidationException $e, Request $request) {
            return Response::render(
                HttpResponse::HTTP_BAD_REQUEST,
                $e->getMessage(),
                $request->all(),
                $request->isJson()
            );
        });

        $exceptions->render(function (NotFoundHttpException $e, Request $request) {
            Log::error('Not found Exception caught: ' . $e->getMessage());
            return Response::render($e->getStatusCode(), $e->getMessage(), $request->all(), $request->isJson());
        });
    })->withCommands([
        InitialiseDB::class
    ])->create();
