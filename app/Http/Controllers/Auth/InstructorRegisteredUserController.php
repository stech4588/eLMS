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
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
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
    public function store(Request $request)
    {
        $success = false;
        $user = null;

        try {
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
                try {
                    $image = $request->file('profile_picture');
                    $imageName = 'profile_' . time() . '.' . $image->getClientOriginalExtension();
                    $image->move(public_path('profile-pictures'), $imageName);
                    $profilePicturePath = 'profile-pictures/' . $imageName;
                } catch (\Exception $e) {
                    Log::warning("Failed to upload profile picture: " . $e->getMessage());
                    // Continue without profile picture
                }
            }

            // Use database transaction to ensure data consistency
            DB::beginTransaction();
            
            try {
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

                // Handle linkedin_programs - can be array, string, or null
                $linkedinPrograms = $request->input('linkedin_programs');
                if (!empty($linkedinPrograms)) {
                    if (is_array($linkedinPrograms)) {
                        // Filter out any empty values and implode
                        $linkedinPrograms = array_filter($linkedinPrograms, function($value) {
                            return !empty($value);
                        });
                        $instructorData['linkedin_programs'] = !empty($linkedinPrograms) ? implode(', ', $linkedinPrograms) : null;
                    } else {
                        // If it's already a string, use it directly
                        $instructorData['linkedin_programs'] = $linkedinPrograms;
                    }
                } else {
                    $instructorData['linkedin_programs'] = null;
                }
                
                Instructor::create($instructorData);

                DB::commit();
                $success = true;
            } catch (\Exception $e) {
                DB::rollBack();
                Log::error("Failed to create instructor registration: " . $e->getMessage(), [
                    'trace' => $e->getTraceAsString(),
                    'file' => $e->getFile(),
                    'line' => $e->getLine()
                ]);
                throw $e;
            }

            // Prepare redirect response immediately after successful commit
            // This ensures we always return a response even if post-processing fails
            if ($success && $user) {
                // Prepare redirect first, before any post-processing
                try {
                    $redirectResponse = redirect()->route('login')->with('status', 'Your instructor application has been submitted and is pending approval.');
                } catch (\Throwable $e) {
                    // Fallback to simple redirect if route fails
                    Log::warning("Failed to generate login route redirect: " . $e->getMessage());
                    $redirectResponse = redirect('/login')->with('status', 'Your instructor application has been submitted and is pending approval.');
                }

                // All post-processing (events, emails, notifications) - completely non-blocking
                // These run AFTER we've prepared the response, so they can't affect the redirect
                try {
                    // Fire registered event (non-blocking)
                    event(new Registered($user));
                } catch (\Throwable $e) {
                    Log::warning("Failed to fire Registered event: " . $e->getMessage(), [
                        'user_id' => $user->id,
                        'trace' => $e->getTraceAsString()
                    ]);
                }

                try {
                    // Notify admins via database notification (non-blocking)
                    $admins = User::where('type', 'admin')->get();
                    if ($admins->isNotEmpty()) {
                        Notification::send($admins, new NewInstructorRegisteredNotification($user));
                    }
                } catch (\Throwable $e) {
                    Log::warning("Failed to send new instructor notification: " . $e->getMessage(), [
                        'user_id' => $user->id
                    ]);
                }

                try {
                    // Notify admin via email (non-blocking)
                    Mail::to('mbmuniversity1@gmail.com')->send(new NewInstructorNotification($user));
                } catch (\Throwable $e) {
                    Log::warning("Failed to send new instructor notification email: " . $e->getMessage(), [
                        'user_id' => $user->id
                    ]);
                }

                // Return the redirect response - this happens regardless of post-processing success/failure
                return $redirectResponse;
            }
        } catch (\Illuminate\Validation\ValidationException $e) {
            // Re-throw validation exceptions so Inertia can handle them properly
            throw $e;
        } catch (\Throwable $e) {
            Log::error("Instructor registration error: " . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'request_data' => $request->except(['password', 'password_confirmation'])
            ]);
            
            // Always return a redirect, never let it fail
            return redirect()->back()->withErrors([
                'error' => 'An error occurred during registration. Please try again or contact support.'
            ])->withInput();
        }
    }
}
