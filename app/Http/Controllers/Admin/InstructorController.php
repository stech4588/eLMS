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
        if ($user->role_id !== 2 || !$user->instructor) {
            abort(404);
        }
        $user->load('instructor');
        return Inertia::render('Admin/Instructors/Show', [
            'instructorUser' => $user,
            'source' => $request->query('source'),
        ]);
    }

    public function approve(Instructor $instructor): RedirectResponse
    {
        $instructor->status = 'approved';
        $instructor->save();

        $instructor->user->is_active = true;
        $instructor->user->save();

        try {
            Mail::to($instructor->user->email)->send(new InstructorApproved($instructor->user));
        } catch (\Exception $e) {
            Log::error("Failed to send approval email: " . $e->getMessage());
        }

        return back()->with('success', 'Instructor approved successfully.');
    }

    public function reject(Request $request, Instructor $instructor): RedirectResponse
    {
        $request->validate(['reason' => 'required|string|min:10']);

        $instructor->status = 'rejected';
        $instructor->save();

        $instructor->user->is_active = false;
        $instructor->user->save();

        try {
            Mail::to($instructor->user->email)->send(new InstructorRejected($instructor->user, $request->reason));
        } catch (\Exception $e) {
            Log::error("Failed to send rejection email: " . $e->getMessage());
        }

        return back()->with('success', 'Instructor rejected successfully.');
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
        if ($user->role_id !== 2 || $user->instructor->status !== 'approved') {
            return back()->with('error', 'This action cannot be performed.');
        }

        $user->is_active = !$user->is_active;
        $user->save();

        return back()->with('success', 'Instructor status updated successfully.');
    }
}
