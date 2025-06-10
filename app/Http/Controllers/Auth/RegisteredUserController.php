<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Inertia\Inertia;
use Inertia\Response;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): Response
    {
        return Inertia::render('Auth/Register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => 'required|string|max:255',
            //'company_name' => 'required|string|max:255',
            'email' => 'required|string|lowercase|email|max:255|unique:'.User::class,
            //'num_employees' => 'required|string|max:255',
            'password' => ['required', Rules\Password::defaults()],
            //'phone_country_code' => 'required|string|max:10',
            'phone_number' => 'required|string|max:20',
            'agree_to_terms' => 'accepted',
        ]);

        $user = User::create([
            'name' => $request->name,
            //'company_name' => $request->company_name,
            'email' => $request->email,
            //'num_employees' => $request->num_employees,
            'password' => Hash::make($request->password),
            //'phone_country_code' => $request->phone_country_code,
            'phone_number' => $request->phone_number,
            'role_id' => 3,
            'type' => 'student',
        ]);

        event(new Registered($user));

        Auth::login($user);

        return redirect(route('dashboard', absolute: false));
    }
}
