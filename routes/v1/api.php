<?php

use App\Http\Controllers\AuthController;
use App\Shared\Response;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {
    Route::get('/', static function () {
        return Response::render(200, 'Welcome to PLMS v1', []);
    });

    Route::prefix('users')->group(function () {
        Route::post('/', AuthController::class . '@register');
        Route::post('login', AuthController::class . '@login');
    });
});
