<?php

namespace App\Http\Controllers;

use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;

class AppleAuthController extends Controller
{
    public function redirect()
    {
        return Socialite::driver('apple')->redirect();
    }

    public function callback()
    {
        try {
            $appleUser = Socialite::driver('apple')->user();

            $user = User::where('email', $appleUser->getEmail())->first();

            if ($user) {
                $user->update([
                    'apple_id' => $appleUser->getId(),
                ]);
                Auth::login($user);
                return redirect('/dashboard');
            } else {
                return redirect('/login')->with('error', 'Your email is not registered. Please sign up first.');
            }
        } catch (Exception $e) {
            return redirect('/login')->with('error', 'Failed to login with Apple.');
        }
    }
} 