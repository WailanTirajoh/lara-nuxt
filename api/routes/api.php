<?php

use App\Http\Controllers\Api\V1\Auth\LoginController;
use App\Http\Controllers\Api\V1\Auth\LogoutController;
use App\Http\Controllers\Api\V1\Auth\ProfileController;
use App\Http\Controllers\Api\V1\Auth\RegisterController;
use App\Http\Controllers\Api\V1\PermissionController;
use App\Http\Controllers\Api\V1\PostController;
use App\Http\Controllers\Api\V1\RoleController;
use App\Http\Controllers\Api\V1\UserController;
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
        Route::post('/login', LoginController::class)->name('login')
            ->middleware('throttle:5,1');
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

        Route::put('/posts/{id}/restore', [PostController::class, 'restore'])->name('posts.restore');
        Route::delete('/posts/{id}/destroy-permanent', [PostController::class, 'destroy_permanent'])
            ->name('posts.restore');
        Route::apiResource('posts', PostController::class);

        Route::apiResource('users', UserController::class);

        Route::apiResource('roles', RoleController::class);

        Route::apiResource('permissions', PermissionController::class)->only('index');
    });
});
