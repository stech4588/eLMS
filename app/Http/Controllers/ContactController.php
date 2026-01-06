<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Notification;
use App\Mail\ContactUsMail;
use App\Notifications\ContactUsNotification;
use App\Models\User;
use Inertia\Inertia;
use Inertia\Response;

class ContactController extends Controller
{
    public function index(): Response
    {
        return Inertia::render('Contact');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'message' => 'required|string|max:5000',
        ]);

        try {
            // Get super admin users (role_id = 1)
            $superAdmins = User::where('role_id', 1)->get();

            if ($superAdmins->isEmpty()) {
                // If no super admin found, try to get admin users (type = 'admin')
                $superAdmins = User::where('type', 'admin')->get();
            }

            // Send email to all super admins
            foreach ($superAdmins as $admin) {
                try {
                    Mail::to($admin->email)->send(new ContactUsMail($validated));
                } catch (\Exception $e) {
                    \Log::error("Failed to send contact email to {$admin->email}: " . $e->getMessage());
                }
            }

            // Send notification to all super admins
            if ($superAdmins->isNotEmpty()) {
                try {
                    Notification::send($superAdmins, new ContactUsNotification($validated));
                } catch (\Exception $e) {
                    \Log::error("Failed to send contact notification: " . $e->getMessage());
                }
            }

            return back()->with('success', 'Your message has been sent successfully. We will get back to you soon.');
        } catch (\Exception $e) {
            \Log::error("Contact form submission error: " . $e->getMessage());
            return back()->withErrors(['error' => 'There was an error sending your message. Please try again later.']);
        }
    }
}
