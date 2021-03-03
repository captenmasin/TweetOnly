<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use Symfony\Component\HttpFoundation\RedirectResponse;

class SocialController extends Controller
{
    public function redirect($provider): RedirectResponse
    {
        return Socialite::driver($provider)->redirect();
    }

    public function callback($provider)
    {
        $twitterSocial = Socialite::driver('twitter')->user();
        $users = User::where(['email' => $twitterSocial->getEmail()])->first();
        if ($users) {
            Auth::login($users);

            return redirect()->route('dashboard');
        } else {
            $user = User::firstOrCreate([
                'name'                => $twitterSocial->getName(),
                'username'            => $twitterSocial->getNickname(),
                'email'               => $twitterSocial->getEmail(),
                'profile_photo_path'  => $twitterSocial->getAvatar(),
                'provider_id'         => $twitterSocial->getId(),
                'provider'            => 'twitter',
                'access_token'        => encrypt($twitterSocial->token),
                'access_token_secret' => encrypt($twitterSocial->tokenSecret),
            ]);

            return redirect()->route('dashboard');
        }
    }
}
