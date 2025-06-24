<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Instructor;
use App\Mail\NewInstructorNotification;
use App\Notifications\NewInstructorRegisteredNotification;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Notification;
use Illuminate\Validation\Rules;
use Inertia\Inertia;
use Inertia\Response;

class InstructorRegisteredUserController extends Controller
{
    /**
     * Display the instructor registration view.
     */
    public function create(): Response
    {
        return Inertia::render('Instructor/myInstructor');
    }

    /**
     * Handle an incoming instructor registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'phone_number' => 'required|string|max:20', // Adjusted max length
            'email' => 'required|string|lowercase|email|max:255|unique:'.User::class,
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'linkedin_url' => 'required|url|max:255',
            'followers' => 'required|string|max:255',
            'linkedin_programs' => 'nullable|array', // Assuming this comes as an array of selected programs
            'linkedin_programs.*' => 'string|max:255',
            'teaching_language' => 'required|string|max:255', // Assuming one language is selected
            'profile_picture' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $profilePicturePath = null;
        if ($request->hasFile('profile_picture')) {
            $image = $request->file('profile_picture');
            $imageName = 'profile_' . time() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('profile-pictures'), $imageName);
            $profilePicturePath = 'profile-pictures/' . $imageName;
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone_number' => $request->phone_number,
            'password' => Hash::make($request->password),
            'role_id' => 2, // Role ID for Instructor
            'type' => 'instructor', // Type for Instructor
            'is_active' => 0, // Set as inactive by default for instructors
            'profile_picture' => $profilePicturePath,
        ]);

        $instructorData = [
            'user_id' => $user->id,
            'linkedin_url' => $request->linkedin_url,
            'followers' => $request->followers,
            'teaching_language' => $request->teaching_language,
            'status' => 'pending',
        ];

        if ($request->has('linkedin_programs') && is_array($request->linkedin_programs)) {
            // Store as a comma-separated string, or JSON encode if preferred
            $instructorData['linkedin_programs'] = implode(', ', $request->linkedin_programs);
        } else {
            // Handle cases where it might be a single string or not provided
            $instructorData['linkedin_programs'] = $request->input('linkedin_programs');
        }
        
        Instructor::create($instructorData);

        event(new Registered($user));

        // Notify admins via database notification
        $admins = User::where('type', 'admin')->get();
        Notification::send($admins, new NewInstructorRegisteredNotification($user));

        // Notify admin via email
        Mail::to('teststechlms@gmail.com')->send(new NewInstructorNotification($user));

        // Auth::login($user);

        // Consider redirecting to an instructor-specific dashboard or page
        return redirect(route('login'))->with('status', 'Your instructor application has been submitted and is pending approval.'); 
    }
}
