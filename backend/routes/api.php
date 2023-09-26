<?php

use App\Http\Controllers\Api\V1\Auth\LoginController;
use App\Http\Controllers\Api\V1\Auth\LogoutController;
use App\Http\Controllers\Api\V1\Auth\ProfileController;
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

Route::group([
    'as' => 'api.',
    'prefix' => '/v1',
    'middleware' => ['cors']
], function () {
    // Unauthenticated
    Route::prefix('/auth')->group(function () {
        Route::post('/login', LoginController::class)->name('login');
        Route::post('/register', RegisterController::class)->name('register');
    });

    // Authtenticated
    Route::group([
        'middleware' => [
            'auth:sanctum'
        ],
    ], function () {
        Route::prefix('/auth')->group(function () {
            Route::get('/profile', ProfileController::class)->name('profile');
            Route::delete('/logout', LogoutController::class)->name('logout');
        });

        Route::apiResource('posts', PostController::class);
    });
});
