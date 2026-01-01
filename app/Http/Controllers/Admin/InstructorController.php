<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Instructor;
use App\Mail\InstructorApproved;
use App\Mail\InstructorRejected;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use Illuminate\Http\RedirectResponse;

class InstructorController extends Controller
{
    public function index()
    {
        $instructors = User::where('role_id', 2)
            ->with('instructor')
            ->orderBy('created_at', 'desc')
            ->get();
        
        return Inertia::render('Admin/Instructors/Index', [
            'instructors' => $instructors
        ]);
    }

    public function show(Request $request, User $user)
    {
        // if ($user->role_id !== 2 || !$user->instructor) {
        //     abort(404);
        // }

        try {
            $user->load('instructor');
            return Inertia::render('Admin/Instructors/Show', [
                'instructorUser' => $user,
                'source' => $request->query('source'),
            ]);
        } catch (\Exception $e) {
            Log::error("Failed to show instructor details: " . $e->getMessage());
            return back()->with('error', 'An error occurred while fetching instructor details.');
        }
    }

    public function approve(Instructor $instructor): RedirectResponse
    {
        try {
            // Load the user relationship if not already loaded
            if (!$instructor->relationLoaded('user')) {
                $instructor->load('user');
            }

            // Check if user exists
            if (!$instructor->user) {
                Log::error("Instructor {$instructor->id} has no associated user");
                return back()->with('error', 'Instructor record is missing associated user. Please contact support.');
            }

            // Use database transaction to ensure data consistency
            DB::beginTransaction();

            try {
                $instructor->status = 'approved';
                $instructor->save();

                $instructor->user->is_active = true;
                $instructor->user->save();

                DB::commit();
            } catch (\Exception $e) {
                DB::rollBack();
                Log::error("Failed to approve instructor {$instructor->id}: " . $e->getMessage(), [
                    'trace' => $e->getTraceAsString()
                ]);
                return back()->with('error', 'Failed to approve instructor. Please try again or contact support.');
            }

            // Send email notification (non-blocking)
            try {
                Mail::to($instructor->user->email)->send(new InstructorApproved($instructor->user));
            } catch (\Exception $e) {
                Log::error("Failed to send approval email to {$instructor->user->email}: " . $e->getMessage());
                // Don't fail the approval if email fails
            }

            return back()->with('success', 'Instructor approved successfully.');
        } catch (\Exception $e) {
            Log::error("Unexpected error in approve method for instructor {$instructor->id}: " . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);
            return back()->with('error', 'An unexpected error occurred. Please try again or contact support.');
        }
    }

    public function reject(Request $request, Instructor $instructor): RedirectResponse
    {
        try {
            $request->validate(['reason' => 'required|string|min:10']);

            // Load the user relationship if not already loaded
            if (!$instructor->relationLoaded('user')) {
                $instructor->load('user');
            }

            // Check if user exists
            if (!$instructor->user) {
                Log::error("Instructor {$instructor->id} has no associated user");
                return back()->with('error', 'Instructor record is missing associated user. Please contact support.');
            }

            // Use database transaction to ensure data consistency
            DB::beginTransaction();

            try {
                $instructor->status = 'rejected';
                $instructor->save();

                $instructor->user->is_active = false;
                $instructor->user->save();

                DB::commit();
            } catch (\Exception $e) {
                DB::rollBack();
                Log::error("Failed to reject instructor {$instructor->id}: " . $e->getMessage(), [
                    'trace' => $e->getTraceAsString()
                ]);
                return back()->with('error', 'Failed to reject instructor. Please try again or contact support.');
            }

            // Send email notification (non-blocking)
            try {
                Mail::to($instructor->user->email)->send(new InstructorRejected($instructor->user, $request->reason));
            } catch (\Exception $e) {
                Log::error("Failed to send rejection email to {$instructor->user->email}: " . $e->getMessage());
                // Don't fail the rejection if email fails
            }

            return back()->with('success', 'Instructor rejected successfully.');
        } catch (\Illuminate\Validation\ValidationException $e) {
            throw $e;
        } catch (\Exception $e) {
            Log::error("Unexpected error in reject method for instructor {$instructor->id}: " . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);
            return back()->with('error', 'An unexpected error occurred. Please try again or contact support.');
        }
    }

    public function destroy(User $user): RedirectResponse
    {
        if ($user->role_id !== 2) {
            return back()->with('error', 'This user is not an instructor.');
        }

        if ($user->instructor) {
            $user->instructor->delete();
        }
        $user->delete();

        return redirect()->route('admin.instructors.index')->with('success', 'Instructor deleted successfully.');
    }

    public function toggleStatus(User $user): RedirectResponse
    {
        try {
            if ($user->role_id !== 2) {
                return back()->with('error', 'This user is not an instructor.');
            }

            // Load instructor relationship if not loaded
            if (!$user->relationLoaded('instructor')) {
                $user->load('instructor');
            }

            if (!$user->instructor) {
                Log::error("User {$user->id} has no associated instructor record");
                return back()->with('error', 'Instructor record not found. Please contact support.');
            }

            if ($user->instructor->status !== 'approved') {
                return back()->with('error', 'This action can only be performed on approved instructors.');
            }

            $user->is_active = !$user->is_active;
            $user->save();

            return back()->with('success', 'Instructor status updated successfully.');
        } catch (\Exception $e) {
            Log::error("Failed to toggle instructor status for user {$user->id}: " . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);
            return back()->with('error', 'An error occurred while updating instructor status. Please try again.');
        }
    }
}
