<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Laravel\Socialite\Socialite;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class SocialAuthController extends Controller
{

    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    public function handleGoogleCallback()
    {
        $userGoogle = Socialite::driver('google')->user();
        try {
            $user = User::where('email', $userGoogle->getEmail())->first();
            if ($user) {
                $user->update(['social_id' => $userGoogle->getId(), 'social_type' => 'google', 'avatar' => $userGoogle->getAvatar()]);
            } else {
                $user = User::create([
                    'name' => $userGoogle->getName(),
                    'email' => $userGoogle->getEmail(),
                    'social_id' => $userGoogle->getId(),
                    'social_type' => 'google',
                    'avatar' => $userGoogle->getAvatar(),
                    'password' => bcrypt(Str::random(16)),
                    'email_verified_at' => now()
                ]);
            }

            Auth::login($user);
            return redirect()->intended('/');
        } catch (\Exception $e) {
            return redirect('/login')->with('error', 'Gagal login dengan Google : ' . $e->getMessage());
        }
    }
}
