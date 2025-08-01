<?php

use App\Http\Controllers\Api\V1\WelcomeController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {

    Route::get('/welcome', [WelcomeController::class, 'index']);
});
