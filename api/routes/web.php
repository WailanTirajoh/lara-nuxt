<?php

use App\Http\Controllers\Api\V1\Auth\Socialite\GithubController;
use Illuminate\Support\Facades\Route;
use Laravel\Socialite\Facades\Socialite;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return response()->json([
        'status' => 'Running',
    ]);
});

Route::get('/sign-in/github', function () {
    return Socialite::driver('github')->redirect();
});

Route::get('/sign-in/github/callback', GithubController::class);
