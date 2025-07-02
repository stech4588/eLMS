<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Topic;
use App\Models\Invoice;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): Response
    {
        $topics = Topic::all();
        $user = Auth::user();

        return Inertia::render('Auth/Register', [
            'topics' => $topics,
            'auth' => [
                'user' => $user ? [
                    'name' => $user->name,
                    'email' => $user->email,
                ] : null,
            ]
        ]);
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        if (Auth::check()) {
            $user = Auth::user();
            $request->validate([
                // 'name' => 'required|string|max:255',
                // 'email' => 'required|string|lowercase|email|max:255|unique:users,email,'.$user->id,
                'phone_number' => 'required|string|max:20',
                'primary_learning_goal' => 'required|string',
                'preferred_topics' => 'required|array',
                'preferred_topics.*' => 'exists:topics,id',
                'resume' => 'nullable|file|mimes:pdf,doc,docx|max:2048',
                'profile_picture' => 'nullable|image|max:2048',
                'agree_to_terms' => 'accepted',
            ]);

            $resumePath = $user->resume_path;
            if ($request->hasFile('resume')) {
                $file = $request->file('resume');
                $filename = 'resume_' . time() . '.' . $file->getClientOriginalExtension();
                $file->move(public_path('resumes'), $filename);
                $resumePath = 'resumes/' . $filename;
            }

            $profilePicturePath = $user->profile_picture;
            if ($request->hasFile('profile_picture')) {
                $file = $request->file('profile_picture');
                $filename = 'profile_' . time() . '.' . $file->getClientOriginalExtension();
                $file->move(public_path('profile-pictures'), $filename);
                $profilePicturePath = 'profile-pictures/' . $filename;
            }

            $user->update([
                // 'name' => $request->name,
                // 'email' => $request->email,
                'phone_number' => $request->phone_number,
                'primary_learning_goal' => $request->primary_learning_goal,
                'preferred_topic_ids' => $request->preferred_topics,
                'resume_path' => $resumePath,
                'profile_picture' => $profilePicturePath,
            ]);

            return redirect(route('dashboard', absolute: false));
        }


        // $request->validate([
        //     'name' => 'required|string|max:255',
        //     'email' => 'required|string|lowercase|email|max:255|unique:'.User::class,
        //     'password' => ['required', Rules\Password::defaults()],
        //     'phone_number' => 'required|string|max:20',
        //     'primary_learning_goal' => 'required|string',
        //     'preferred_topics' => 'required|array',
        //     'preferred_topics.*' => 'exists:topics,id',
        //     'resume' => 'required|file|mimes:pdf,doc,docx|max:2048',
        //     'profile_picture' => 'required|image|max:2048',
        //     'agree_to_terms' => 'accepted',
        // ]);

        // $resumePath = null;
        // if ($request->hasFile('resume')) {
        //     $file = $request->file('resume');
        //     $filename = 'resume_' . time() . '.' . $file->getClientOriginalExtension();
        //     $file->move(public_path('resumes'), $filename);
        //     $resumePath = 'resumes/' . $filename;
        // }

        // $profilePicturePath = null;
        // if ($request->hasFile('profile_picture')) {
        //     $file = $request->file('profile_picture');
        //     $filename = 'profile_' . time() . '.' . $file->getClientOriginalExtension();
        //     $file->move(public_path('profile-pictures'), $filename);
        //     $profilePicturePath = 'profile-pictures/' . $filename;
        // }

        // $user = User::create([
        //     'name' => $request->name,
        //     'email' => $request->email,
        //     'password' => Hash::make($request->password),
        //     'phone_number' => $request->phone_number,
        //     'primary_learning_goal' => $request->primary_learning_goal,
        //     'preferred_topic_ids' => json_encode($request->preferred_topics),
        //     'resume_path' => $resumePath,
        //     'profile_picture' => $profilePicturePath,
        //     'role_id' => 3,
        //     'type' => 'student',
        // ]);

        // event(new Registered($user));

        // Auth::login($user);

        return redirect(route('dashboard', absolute: false));
    }

    public function storeFromPayment(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|lowercase|email|max:255|unique:'.User::class,
            'password' => 'required|string',
            'transaction_id' => 'required|string',
            'amount' => 'required|numeric',
            'plan' => 'required|string',
            'billing_cycle' => 'required|string',
            'payment_method' => 'required|string',
        ]);
    
        DB::beginTransaction();
        try {
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role_id' => 3, // student
                'type' => 'student',
            ]);
    
            Invoice::create([
                'user_id' => $user->id,
                'amount' => $request->amount,
                'payment_method' => $request->payment_method,
                'payment_status' => 'paid',
                'transaction_id' => $request->transaction_id,
                'paid_at' => now(),
                'plan' => $request->plan,
                'billing_cycle' => $request->billing_cycle,
            ]);
    
            DB::commit();
    
            Auth::login($user);
    
            event(new Registered($user));
    
            return redirect()->route('register.complete');
    
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Registration from payment failed: ' . $e->getMessage());
            return back()->withErrors(['payment_error' => 'An error occurred during registration. Please contact support.']);
        }
    }
}
