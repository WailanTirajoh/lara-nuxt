<?php

use App\Http\Controllers\Api\V1\Auth\LoginController;
use App\Http\Controllers\Api\V1\Auth\RegisterController;
use App\Http\Controllers\Api\V1\PostController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::group(["as" => "api.", "prefix" => "/v1"], function () {
    Route::prefix("/auth")->group(function () {
        Route::post('/login', LoginController::class)->name('login');
        Route::post('/register', RegisterController::class)->name('register');
    });

    Route::group([
        'middleware' => [
            'auth:sanctum'
        ],
    ], function () {
        Route::apiResource("posts", PostController::class);
    });
});
