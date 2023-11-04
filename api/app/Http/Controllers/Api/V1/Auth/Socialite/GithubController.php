<?php

namespace App\Http\Controllers\Api\V1\Auth\Socialite;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;
use Laravel\Socialite\Facades\Socialite;

class GithubController extends Controller
{
    public function __invoke()
    {
        $userGithub = Socialite::driver('github')->stateless()->user();

        $user = User::updateOrCreate(['email' => $userGithub->email], [
            'name' => $userGithub->name,
            'email' => $userGithub->email,
            'password' => Hash::make($userGithub->email),
        ]);

        if ($userGithub->avatar) {
            $user
                ->clearMediaCollection('images')
                ->addMediaFromUrl($userGithub->avatar)
                ->toMediaCollection('images');
        }

        if ($user->roles->count() === 0) {
            $user->assignRole('visitor');
        }

        Auth::login($user);

        $accessToken = Auth::user()->createToken('access_token')->plainTextToken;

        return Redirect::to(config('app.cms_url') . '/auth/sso?access_token=' . $accessToken);
    }
}
