<?php

namespace App\Http\Controllers;

use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;

class FacebookAuthController extends Controller
{
    public function redirect()
    {
        return Socialite::driver('facebook')->redirect();
    }

    public function callback()
    {
        try {
            $facebookUser = Socialite::driver('facebook')->user();

            $user = User::where('email', $facebookUser->getEmail())->first();

            if ($user) {
                $user->update([
                    'facebook_id' => $facebookUser->getId(),
                ]);
                Auth::login($user);
                return redirect()->route('dashboard');
            } else {
                return redirect('/login')->with('error', 'Your email is not registered. Please sign up first.');
            }
        } catch (Exception $e) {
            return redirect('/login')->with('error', 'Failed to login with Facebook.');
        }
    }
} 