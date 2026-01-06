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
        \Log::info('Contact form submission started', [
            'ip' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'timestamp' => now()->toDateTimeString(),
        ]);

        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'message' => 'required|string|max:5000',
        ]);

        \Log::info('Contact form validation passed', [
            'email' => $validated['email'],
            'name' => $validated['first_name'] . ' ' . $validated['last_name'],
        ]);

        try {
            // Get super admin users (role_id = 1)
            $superAdmins = User::where('role_id', 1)->get();
            \Log::info('Super admins found by role_id=1', [
                'count' => $superAdmins->count(),
                'admin_ids' => $superAdmins->pluck('id')->toArray(),
                'admin_emails' => $superAdmins->pluck('email')->toArray(),
            ]);

            if ($superAdmins->isEmpty()) {
                // If no super admin found, try to get admin users (type = 'admin')
                $superAdmins = User::where('type', 'admin')->get();
                \Log::info('Super admins found by type=admin', [
                    'count' => $superAdmins->count(),
                    'admin_ids' => $superAdmins->pluck('id')->toArray(),
                    'admin_emails' => $superAdmins->pluck('email')->toArray(),
                ]);
            }

            if ($superAdmins->isEmpty()) {
                \Log::warning('No super admin users found to notify', [
                    'role_id_1_count' => User::where('role_id', 1)->count(),
                    'type_admin_count' => User::where('type', 'admin')->count(),
                    'all_users_count' => User::count(),
                ]);
            }

            // Send email to all super admins
            $emailsSent = 0;
            $emailsFailed = 0;
            foreach ($superAdmins as $admin) {
                try {
                    \Log::info('Attempting to send email', [
                        'admin_id' => $admin->id,
                        'admin_email' => $admin->email,
                    ]);
                    Mail::to($admin->email)->send(new ContactUsMail($validated));
                    $emailsSent++;
                    \Log::info('Email sent successfully', [
                        'admin_id' => $admin->id,
                        'admin_email' => $admin->email,
                    ]);
                } catch (\Exception $e) {
                    $emailsFailed++;
                    \Log::error("Failed to send contact email", [
                        'admin_id' => $admin->id,
                        'admin_email' => $admin->email,
                        'error' => $e->getMessage(),
                        'trace' => $e->getTraceAsString(),
                    ]);
                }
            }

            \Log::info('Email sending completed', [
                'total_admins' => $superAdmins->count(),
                'emails_sent' => $emailsSent,
                'emails_failed' => $emailsFailed,
            ]);

            // Send notification to all super admins
            if ($superAdmins->isNotEmpty()) {
                try {
                    \Log::info('Attempting to send notifications', [
                        'admin_count' => $superAdmins->count(),
                        'admin_ids' => $superAdmins->pluck('id')->toArray(),
                        'notification_class' => ContactUsNotification::class,
                    ]);

                    // Check if notification uses queue
                    $notificationInstance = new ContactUsNotification($validated);
                    $usesQueue = $notificationInstance instanceof \Illuminate\Contracts\Queue\ShouldQueue;
                    \Log::info('Notification queue status', [
                        'uses_queue' => $usesQueue,
                        'queue_connection' => config('queue.default'),
                    ]);

                    Notification::send($superAdmins, $notificationInstance);
                    
                    \Log::info('Notifications sent successfully', [
                        'admin_count' => $superAdmins->count(),
                        'queued' => $usesQueue,
                    ]);

                    // If using queue, verify jobs were dispatched
                    if ($usesQueue) {
                        \Log::info('Notifications queued - verify queue worker is running');
                    }

                } catch (\Exception $e) {
                    \Log::error("Failed to send contact notification", [
                        'error' => $e->getMessage(),
                        'trace' => $e->getTraceAsString(),
                        'admin_count' => $superAdmins->count(),
                    ]);
                }
            } else {
                \Log::warning('Skipping notification send - no super admins found');
            }

            \Log::info('Contact form submission completed successfully', [
                'emails_sent' => $emailsSent,
                'emails_failed' => $emailsFailed,
                'notifications_sent' => $superAdmins->isNotEmpty() ? 'yes' : 'no',
            ]);

            return back()->with('success', 'Your message has been sent successfully. We will get back to you soon.');
        } catch (\Exception $e) {
            \Log::error("Contact form submission error", [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);
            return back()->withErrors(['error' => 'There was an error sending your message. Please try again later.']);
        }
    }
}
