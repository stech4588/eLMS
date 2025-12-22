<?php

namespace App\Http\Controllers;

use App\Models\SubscriptionSetting;
use App\Services\InvoiceService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Inertia\Response;

class SubscriptionSettingController extends Controller
{
    public function __construct(protected InvoiceService $invoiceService)
    {
    }

    protected function ensureSuperAdmin(): void
    {
        $user = Auth::user();
        if (!$user || (int) $user->role_id !== 1) {
            abort(403, 'Only superadmins may access subscription settings.');
        }
    }

    public function edit(): Response
    {
        $this->ensureSuperAdmin();

        return Inertia::render('Invoice/SubscriptionSettings', [
            'settings' => $this->invoiceService->getSettings(),
        ]);
    }

    public function update(Request $request)
    {
        $this->ensureSuperAdmin();

        $data = $request->validate([
            'monthly_due_day' => 'required|integer|min:1|max:28',
            'reminder_offsets' => 'nullable',
            'grace_period_days' => 'required|integer|min:0|max:15',
        ]);

        $offsets = $data['reminder_offsets'];
        if (is_string($offsets)) {
            $offsets = collect(explode(',', $offsets))
                ->map(fn ($value) => (int) trim($value))
                ->filter(fn ($value) => $value >= 0 && $value <= 27)
                ->values()
                ->all();
        } elseif (is_array($offsets)) {
            $offsets = collect($offsets)
                ->map(fn ($value) => (int) $value)
                ->filter(fn ($value) => $value >= 0 && $value <= 27)
                ->values()
                ->all();
        } else {
            $offsets = [];
        }

        $settings = $this->invoiceService->getSettings();
        $settings->update([
            'monthly_due_day' => $data['monthly_due_day'],
            'reminder_offsets' => $offsets,
            'grace_period_days' => $data['grace_period_days'],
        ]);

        return redirect()->back()->with('success', 'Subscription settings updated.');
    }
}

