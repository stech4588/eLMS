<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class SettingsController extends Controller
{
    /**
     * Display the user's settings page.
     *
     * @return \Inertia\Response
     */
    public function index()
    {
        return Inertia::render('Settings/Index', [
            'user' => Auth::user()->load('emailNotificationSettings'),
        ]);
    }

    /**
     * Update the user's email notification settings.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request)
    {
        $user = Auth::user();

        $validatedData = $request->validate([
            'receives_course_completion_emails' => 'sometimes|boolean',
            'receives_course_reminder_emails' => 'sometimes|boolean',
            'receives_marketing_emails' => 'sometimes|boolean',
            'receives_motivational_quote_emails' => 'sometimes|boolean',
            'receives_new_course_notification_emails' => 'sometimes|boolean',
            'receives_prompt_generated_emails' => 'sometimes|boolean',
            'receives_wellness_checkin_emails' => 'sometimes|boolean',
        ]);

        foreach ($validatedData as $key => $value) {
            $user->emailNotificationSettings()->updateOrCreate(
                ['key' => $key],
                ['value' => $value]
            );
        }

        return redirect()->back()->with('success', 'Settings updated successfully.');
    }
}
