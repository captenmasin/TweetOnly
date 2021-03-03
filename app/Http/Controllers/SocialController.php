<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class SocialController extends Controller
{
    public function redirect($provider)
    {
        return Socialite::driver($provider)->redirect();
    }

    public function callback()
    {
        $twitterSocial = Socialite::driver('twitter')->user();
        $users = User::where(['email' => $twitterSocial->getEmail()])->first();
        if ($users) {
            Auth::login($users);
            return redirect('/home');
        } else {
            $user = User::firstOrCreate([
                'name'        => $twitterSocial->getName(),
                'email'       => $twitterSocial->getEmail(),
                'image'       => $twitterSocial->getAvatar(),
                'provider_id' => $twitterSocial->getId(),
                'provider'    => 'twitter',
            ]);
            return redirect()->route('dashboard');
        }
    }
}
