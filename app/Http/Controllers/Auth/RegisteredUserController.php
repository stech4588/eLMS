<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Topic;
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
        $topics = Topic::all();
        return Inertia::render('Auth/Register', [
            'topics' => $topics,
        ]);
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
            'primary_learning_goal' => 'required|string',
            'preferred_topics' => 'required|array',
            'preferred_topics.*' => 'exists:topics,id',
            'resume' => 'required|file|mimes:pdf,doc,docx|max:2048',
            'profile_picture' => 'required|image|max:2048',
            'agree_to_terms' => 'accepted',
        ]);

        $resumePath = null;
        if ($request->hasFile('resume')) {
            $file = $request->file('resume');
            $filename = 'resume_' . time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('resumes'), $filename);
            $resumePath = 'resumes/' . $filename;
        }

        $profilePicturePath = null;
        if ($request->hasFile('profile_picture')) {
            $file = $request->file('profile_picture');
            $filename = 'profile_' . time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('profile-pictures'), $filename);
            $profilePicturePath = 'profile-pictures/' . $filename;
        }

        $user = User::create([
            'name' => $request->name,
            //'company_name' => $request->company_name,
            'email' => $request->email,
            //'num_employees' => $request->num_employees,
            'password' => Hash::make($request->password),
            //'phone_country_code' => $request->phone_country_code,
            'phone_number' => $request->phone_number,
            'primary_learning_goal' => $request->primary_learning_goal,
            'preferred_topic_ids' => $request->preferred_topics,
            'resume_path' => $resumePath,
            'profile_picture' => $profilePicturePath,
            'role_id' => 3,
            'type' => 'student',
        ]);

        event(new Registered($user));

        Auth::login($user);

        return redirect(route('dashboard', absolute: false));
    }
}
