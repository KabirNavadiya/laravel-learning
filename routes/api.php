<?php

use App\Http\Controllers\Api\V1\RegisterController;
use App\Http\Controllers\Api\V1\WelcomeController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {

    Route::get('/welcome', [WelcomeController::class, 'index']);

    Route::prefix('auth')->group(function () {
        Route::post('/register', [RegisterController::class, 'register']);

    });

    Route::middleware('auth:sanctum')->group(function () {});

});
