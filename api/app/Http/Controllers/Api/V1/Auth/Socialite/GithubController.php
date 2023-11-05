<?php

namespace App\Http\Controllers\Api\V1\Auth\Socialite;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Laravel\Socialite\Facades\Socialite;

class GithubController extends Controller
{
    public function __invoke(): RedirectResponse
    {
        $userGithub = Socialite::driver('github')->stateless()->user();

        $user = User::firstOrNew(['email' => $userGithub->email]);
        $user->name = $userGithub->name;
        $user->email = $userGithub->email;

        if (! $user->exists) {
            $user->password = Hash::make($userGithub->email);
            $user->save();
            $user->assignRole('visitor');
        } else {
            $user->save();
        }

        if ($userGithub->avatar) {
            $user->clearMediaCollection('images')
                ->addMediaFromUrl($userGithub->avatar)
                ->toMediaCollection('images');
        }

        Auth::login($user);

        $accessToken = Auth::user()->createToken('access_token')->plainTextToken;

        return redirect()->to(config('app.cms_url').'/auth/sso?access_token='.$accessToken);
    }
}
