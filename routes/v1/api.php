<?php

use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {
    Route::get('/', function () {
        return response()->json(['message' => 'Welcome to API v1']);
    });

    // Add other versioned routes here
});
