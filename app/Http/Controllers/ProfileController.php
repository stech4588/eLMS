<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\File;
use Inertia\Inertia;
use Inertia\Response;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): Response
    {
        return Inertia::render('Profile/Edit', [
            'mustVerifyEmail' => $request->user() instanceof MustVerifyEmail,
            'status' => session('status'),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $request->user()->fill($request->validated());

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();

        return Redirect::to('/profile');
    }

    /**
     * Upload profile picture.
     */
    public function uploadPicture(Request $request)
    {
        try {
            Log::info("=== Profile Picture Upload Started ===", [
                'user_id' => $request->user()->id,
            ]);
            
            $request->validate([
                'profile_picture' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            ]);

            $user = $request->user();

            // Delete old profile picture if exists
            if ($user->profile_picture && file_exists(public_path($user->profile_picture))) {
                try {
                    @unlink(public_path($user->profile_picture));
                    Log::info("Old profile picture deleted", ['path' => $user->profile_picture]);
                } catch (\Exception $e) {
                    Log::warning("Failed to delete old profile picture: " . $e->getMessage());
                    // Continue even if deletion fails
                }
            }

            // Ensure directory exists
            $profilePicturesDir = public_path('profile-pictures');
            if (!File::exists($profilePicturesDir)) {
                File::makeDirectory($profilePicturesDir, 0755, true);
                Log::info("Created profile-pictures directory");
            }

            // Upload new profile picture
            $file = $request->file('profile_picture');
            $filename = 'profile_' . time() . '.' . $file->getClientOriginalExtension();
            
            Log::info("Attempting to move profile picture", ['filename' => $filename]);
            $file->move($profilePicturesDir, $filename);
            $profilePicturePath = 'profile-pictures/' . $filename;
            
            Log::info("Profile picture uploaded successfully", ['path' => $profilePicturePath]);

            $user->profile_picture = $profilePicturePath;
            $user->save();
            
            Log::info("User profile picture updated in database", ['user_id' => $user->id]);

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Profile picture uploaded successfully.',
                    'profile_picture' => $profilePicturePath,
                ]);
            }

            return redirect()->back()->with('message', 'Profile picture uploaded successfully.');
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::warning("Validation failed for profile picture upload", [
                'errors' => $e->errors(),
            ]);
            throw $e; // Re-throw validation exceptions
        } catch (\Throwable $e) {
            Log::error("=== Profile Picture Upload Failed ===", [
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString(),
                'user_id' => $request->user()->id ?? null,
            ]);
            
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'An error occurred while uploading the profile picture. Please try again.',
                ], 500);
            }
            
            return redirect()->back()->withErrors([
                'error' => 'An error occurred while uploading the profile picture. Please try again.'
            ]);
        }
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validate([
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
