<?php

use App\Http\Controllers\AuthController;
use App\Infrastructure\Middleware\ValidateApiKey;
use App\Shared\Response;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {
    Route::get('/', static function () {
        return Response::render(200, 'Welcome to PLMS v1', []);
    });

    Route::prefix('users')->middleware([ValidateApiKey::class])->group(function () {
        Route::post('/', AuthController::class . '@register');
        Route::post('login', AuthController::class . '@login');
    });
});
