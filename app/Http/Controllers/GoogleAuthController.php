<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;



class GoogleAuthController extends Controller
{
    //
    public function redirect()
    {
        return Socialite::driver('google')->redirect();
    }

    public function callback()
    {
        try {
        $googleUser = Socialite::driver('google')->user();

         // Check if email already exists in your users table
        $user = User::where('email', $googleUser->getEmail())->first();

        if ($user) {
            $user->update([
                'google_id' => $googleUser->getId(),
            ]);
            Auth::login($user);
            return redirect()->route('dashboard');
        } else {
            // Email not found — do not allow login
            return redirect('/login')->with('error', 'Your email is not registered.');
        }
    } catch (Exception $e) {
        return redirect('/login')->with('error', 'Failed to login with Google.');
    }
    }
    }

