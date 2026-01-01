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
        Log::info("=== Instructor Registration Started ===", [
            'email' => $request->input('email'),
            'name' => $request->input('name'),
            'ip' => $request->ip(),
            'user_agent' => $request->userAgent()
        ]);

        $success = false;
        $user = null;

        try {
            Log::info("Step 1: Starting validation");
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
            Log::info("Step 1: Validation passed");

            Log::info("Step 2: Processing profile picture");
            $profilePicturePath = null;
            if ($request->hasFile('profile_picture')) {
                try {
                    $image = $request->file('profile_picture');
                    $imageName = 'profile_' . time() . '.' . $image->getClientOriginalExtension();
                    Log::info("Step 2.1: Attempting to move profile picture", ['filename' => $imageName]);
                    $image->move(public_path('profile-pictures'), $imageName);
                    $profilePicturePath = 'profile-pictures/' . $imageName;
                    Log::info("Step 2.2: Profile picture uploaded successfully", ['path' => $profilePicturePath]);
                } catch (\Exception $e) {
                    Log::warning("Step 2: Failed to upload profile picture: " . $e->getMessage(), [
                        'trace' => $e->getTraceAsString()
                    ]);
                    // Continue without profile picture
                }
            } else {
                Log::info("Step 2: No profile picture provided");
            }

            Log::info("Step 3: Starting database transaction");
            // Use database transaction to ensure data consistency
            DB::beginTransaction();
            
            try {
                Log::info("Step 4: Creating user record");
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
                Log::info("Step 4: User created successfully", ['user_id' => $user->id, 'email' => $user->email]);

                Log::info("Step 5: Preparing instructor data");
                $instructorData = [
                    'user_id' => $user->id,
                    'linkedin_url' => $request->linkedin_url,
                    'followers' => $request->followers,
                    'teaching_language' => $request->teaching_language,
                    'status' => 'pending',
                ];

                // Handle linkedin_programs - can be array, string, or null
                $linkedinPrograms = $request->input('linkedin_programs');
                Log::info("Step 5.1: Processing linkedin_programs", ['input' => $linkedinPrograms, 'type' => gettype($linkedinPrograms)]);
                if (!empty($linkedinPrograms)) {
                    if (is_array($linkedinPrograms)) {
                        // Filter out any empty values and implode
                        $linkedinPrograms = array_filter($linkedinPrograms, function($value) {
                            return !empty($value);
                        });
                        $instructorData['linkedin_programs'] = !empty($linkedinPrograms) ? implode(', ', $linkedinPrograms) : null;
                        Log::info("Step 5.2: Processed linkedin_programs array", ['result' => $instructorData['linkedin_programs']]);
                    } else {
                        // If it's already a string, use it directly
                        $instructorData['linkedin_programs'] = $linkedinPrograms;
                        Log::info("Step 5.2: Using linkedin_programs as string", ['result' => $instructorData['linkedin_programs']]);
                    }
                } else {
                    $instructorData['linkedin_programs'] = null;
                    Log::info("Step 5.2: linkedin_programs is empty, setting to null");
                }
                
                Log::info("Step 6: Creating instructor record", ['instructor_data' => $instructorData]);
                Instructor::create($instructorData);
                Log::info("Step 6: Instructor record created successfully");

                Log::info("Step 7: Committing database transaction");
                DB::commit();
                $success = true;
                Log::info("Step 7: Database transaction committed successfully");
            } catch (\Exception $e) {
                Log::error("Step 7: Database transaction failed, rolling back", [
                    'error' => $e->getMessage(),
                    'file' => $e->getFile(),
                    'line' => $e->getLine(),
                    'trace' => $e->getTraceAsString()
                ]);
                DB::rollBack();
                Log::error("Step 7: Database transaction rolled back");
                throw $e;
            }

            // Prepare redirect response immediately after successful commit
            // This ensures we always return a response even if post-processing fails
            if ($success && $user) {
                Log::info("Step 8: Preparing redirect response", ['user_id' => $user->id]);
                // Prepare redirect first, before any post-processing
                try {
                    $redirectResponse = redirect()->route('login')->with('status', 'Your instructor application has been submitted and is pending approval.');
                    Log::info("Step 8: Redirect response prepared successfully using route('login')");
                } catch (\Throwable $e) {
                    // Fallback to simple redirect if route fails
                    Log::warning("Step 8: Failed to generate login route redirect, using fallback", [
                        'error' => $e->getMessage(),
                        'trace' => $e->getTraceAsString()
                    ]);
                    $redirectResponse = redirect('/login')->with('status', 'Your instructor application has been submitted and is pending approval.');
                    Log::info("Step 8: Fallback redirect response prepared");
                }

                // All post-processing (events, emails, notifications) - completely non-blocking
                // These run AFTER we've prepared the response, so they can't affect the redirect
                Log::info("Step 9: Starting post-processing (events, emails, notifications)");
                
                try {
                    Log::info("Step 9.1: Firing Registered event");
                    // Fire registered event (non-blocking)
                    event(new Registered($user));
                    Log::info("Step 9.1: Registered event fired successfully");
                } catch (\Throwable $e) {
                    Log::warning("Step 9.1: Failed to fire Registered event", [
                        'user_id' => $user->id,
                        'error' => $e->getMessage(),
                        'trace' => $e->getTraceAsString()
                    ]);
                }

                try {
                    Log::info("Step 9.2: Sending database notifications to admins");
                    // Notify admins via database notification (non-blocking)
                    $admins = User::where('type', 'admin')->get();
                    Log::info("Step 9.2.1: Found admins", ['count' => $admins->count()]);
                    if ($admins->isNotEmpty()) {
                        Notification::send($admins, new NewInstructorRegisteredNotification($user));
                        Log::info("Step 9.2: Database notifications sent successfully");
                    } else {
                        Log::info("Step 9.2: No admins found to notify");
                    }
                } catch (\Throwable $e) {
                    Log::warning("Step 9.2: Failed to send new instructor notification", [
                        'user_id' => $user->id,
                        'error' => $e->getMessage(),
                        'trace' => $e->getTraceAsString()
                    ]);
                }

                try {
                    Log::info("Step 9.3: Sending email notification to admin");
                    // Notify admin via email (non-blocking)
                    Mail::to('mbmuniversity1@gmail.com')->send(new NewInstructorNotification($user));
                    Log::info("Step 9.3: Email notification sent successfully");
                } catch (\Throwable $e) {
                    Log::warning("Step 9.3: Failed to send new instructor notification email", [
                        'user_id' => $user->id,
                        'error' => $e->getMessage(),
                        'trace' => $e->getTraceAsString()
                    ]);
                }

                Log::info("Step 10: Returning redirect response", ['user_id' => $user->id]);
                // Return the redirect response - this happens regardless of post-processing success/failure
                return $redirectResponse;
            } else {
                Log::error("Step 8: Cannot prepare redirect - success: {$success}, user: " . ($user ? $user->id : 'null'));
            }
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::warning("Validation failed", [
                'errors' => $e->errors(),
                'email' => $request->input('email')
            ]);
            // Re-throw validation exceptions so Inertia can handle them properly
            throw $e;
        } catch (\Throwable $e) {
            Log::error("=== Instructor Registration Failed ===", [
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString(),
                'request_data' => $request->except(['password', 'password_confirmation']),
                'class' => get_class($e)
            ]);
            
            // Always return a redirect, never let it fail
            Log::info("Returning error redirect response");
            return redirect()->back()->withErrors([
                'error' => 'An error occurred during registration. Please try again or contact support.'
            ])->withInput();
        }
        
        Log::info("=== Instructor Registration Completed ===", [
            'success' => $success,
            'user_id' => $user ? $user->id : null
        ]);
    }
}
