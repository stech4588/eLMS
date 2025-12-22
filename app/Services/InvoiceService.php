<?php

namespace App\Services;

use App\Models\Invoice;
use App\Models\SubscriptionSetting;
use App\Models\SubscriptionStatus;
use App\Models\SuspensionLog;
use App\Models\User;
use App\Notifications\InvoiceCreatedNotification;
use App\Notifications\InvoiceReminderNotification;
use App\Notifications\PaymentReceivedNotification;
use App\Notifications\SubscriptionSuspendedNotification;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;

class InvoiceService
{
    public function getAll(): Collection
    {
        return Invoice::with(['user', 'details.course'])->get();
    }

    public function getById(int $id): Invoice
    {
        return Invoice::with(['user', 'details.course'])->findOrFail($id);
    }

    public function create(array $data): Invoice
    {
        if (empty($data['user_id'])) {
            throw new \InvalidArgumentException('user_id is required to create an invoice.');
        }

        $billingMonth = isset($data['billing_month']) && $data['billing_month']
            ? Carbon::parse($data['billing_month'])->startOfMonth()
            : now()->startOfMonth();

        $settings = $this->getSettings();
        $dueDay = min($settings->monthly_due_day, $billingMonth->daysInMonth);

        $status = $data['status'] ?? (($data['payment_status'] ?? 'pending') === 'paid' ? 'paid' : 'unpaid');

        if ($status === 'paid' && empty($data['paid_at'])) {
            $data['paid_at'] = now();
        }

        $paymentStatus = $data['payment_status'] ?? ($status === 'paid' ? 'paid' : 'pending');

        $payload = array_merge([
            'billing_month' => $billingMonth->toDateString(),
            'due_date' => $data['due_date'] ?? $billingMonth->copy()->day($dueDay)->toDateString(),
            'status' => $status,
            'payment_status' => $paymentStatus,
            'reminder_count' => $data['reminder_count'] ?? 0,
            'last_reminded_at' => $data['last_reminded_at'] ?? null,
        ], $data);

        return Invoice::create($payload);
    }

    public function normalizeInvoice(Invoice $invoice): void
    {
        $updates = [];
        $settings = $this->getSettings();

        $billingMonth = $invoice->billing_month
            ? Carbon::parse($invoice->billing_month)->startOfMonth()
            : ($invoice->paid_at
                ? $invoice->paid_at->copy()->startOfMonth()
                : ($invoice->created_at ? Carbon::parse($invoice->created_at)->startOfMonth() : now()->startOfMonth()));

        if (!$invoice->billing_month) {
            $updates['billing_month'] = $billingMonth->toDateString();
        }

        $shouldPreserveNullDueDate = $invoice->status === 'paid' && $invoice->paid_at;

        if (!$invoice->due_date && !$shouldPreserveNullDueDate) {
            $dueDay = min($settings->monthly_due_day, $billingMonth->daysInMonth);
            $updates['due_date'] = $billingMonth->copy()->day($dueDay)->toDateString();
        }

        if ($invoice->payment_status === 'paid' && $invoice->status !== 'paid') {
            $updates['status'] = 'paid';
            if (!$invoice->paid_at) {
                $updates['paid_at'] = now();
            }
        } elseif (!$invoice->status) {
            $updates['status'] = $invoice->payment_status === 'paid' ? 'paid' : 'unpaid';
        }

        if (!empty($updates)) {
            $invoice->forceFill($updates)->save();
            $invoice->refresh();
        }
    }

    public function update(int $id, array $data): Invoice
    {
        $invoice = Invoice::findOrFail($id);
        $invoice->update($data);

        return $invoice;
    }

    public function delete(int $id): bool
    {
        return Invoice::findOrFail($id)->delete();
    }

    public function getSettings(): SubscriptionSetting
    {
        return SubscriptionSetting::firstOrCreate(
            ['id' => 1],
            [
                'monthly_due_day' => 5,
                'reminder_offsets' => [0, 2, 4],
                'grace_period_days' => 3,
            ]
        );
    }

    public function createMonthlyInvoice(User $user, ?Carbon $billingDate = null, array $overrides = []): ?Invoice
    {
        if (!empty($user->trial_ends_at) && Carbon::parse($user->trial_ends_at)->isFuture()) {
            return null;
        }

        $runDate = $billingDate ? $billingDate->copy() : now();
        $lastPaidInvoice = $user->invoices()
            ->where('status', 'paid')
            ->orderByDesc('paid_at')
            ->first();
        $latestInvoice = $user->invoices()->latest('billing_month')->first();

        $billingCycle = $overrides['billing_cycle'] ?? $latestInvoice?->billing_cycle ?? config('subscription.default_cycle', 'monthly');
        $plan = $overrides['plan'] ?? $latestInvoice?->plan ?? config('subscription.default_plan');
        $amount = $overrides['amount'] ?? $latestInvoice?->amount ?? config('subscription.default_amount');

        if ($amount <= 0) {
            return null;
        }

        $paymentMethod = $overrides['payment_method'] ?? $latestInvoice?->payment_method;

        $basePaidAt = $lastPaidInvoice?->paid_at ? Carbon::parse($lastPaidInvoice->paid_at) : null;

        if (!$lastPaidInvoice) {
            $billingMonth = $runDate->copy()->startOfMonth();
            $dueDate = null;
        } else {
            if ($billingCycle === 'yearly') {
                $nextAnniversary = $basePaidAt->copy()->addYear();
                if ($runDate->lt($nextAnniversary)) {
                    return null;
                }
                $billingMonth = $nextAnniversary->copy()->startOfMonth();
                $dueDate = $nextAnniversary->copy()->addDays(5);
            } else {
                $nextPeriod = $basePaidAt->copy()->addMonthNoOverflow();
                $billingMonth = $nextPeriod->copy()->startOfMonth();
                $dueDate = $nextPeriod->copy()->addDays(5);
            }
        }

        $existing = Invoice::where('user_id', $user->id)
            ->whereDate('billing_month', $billingMonth->toDateString())
            ->first();

        if ($existing) {
            return $existing;
        }

        $status = $overrides['status'] ?? ($lastPaidInvoice ? 'unpaid' : 'paid');
        $paymentStatus = $status === 'paid' ? 'paid' : 'pending';
        $paidAt = $status === 'paid' ? ($overrides['paid_at'] ?? now()) : null;

        if (!$lastPaidInvoice && $status === 'paid') {
            $dueDate = null;
        }

        $invoice = Invoice::create([
            'user_id' => $user->id,
            'amount' => $amount,
            'plan' => $plan,
            'billing_cycle' => $billingCycle,
            'payment_method' => $paymentMethod,
            'payment_status' => $paymentStatus,
            'transaction_id' => $overrides['transaction_id'] ?? null,
            'paid_at' => $paidAt,
            'billing_month' => $billingMonth->toDateString(),
            'due_date' => $dueDate ? $dueDate->toDateString() : null,
            'status' => $status,
            'notes' => $overrides['notes'] ?? null,
            'reminder_count' => 0,
            'last_reminded_at' => null,
        ]);

        $subscriptionStatus = $user->subscriptionStatus()->firstOrCreate([]);
        if ($subscriptionStatus->state !== 'suspended' && $subscriptionStatus->state !== 'suspended_manual') {
            $subscriptionStatus->state = $status === 'paid' ? 'active' : 'pending';
        }
        $subscriptionStatus->last_invoice_id = $invoice->id;
        if ($status === 'paid') {
            $subscriptionStatus->last_payment_date = $invoice->paid_at;
        }
        $subscriptionStatus->save();

        try {
            $user->notify(new InvoiceCreatedNotification($invoice));
        } catch (\Throwable $e) {
            Log::warning('Failed to send invoice created notification', [
                'invoice_id' => $invoice->id,
                'user_id' => $user->id,
                'error' => $e->getMessage(),
            ]);
        }

        return $invoice;
    }

    public function sendReminderBatch(Carbon $runDate): void
    {
        $settings = $this->getSettings();
        $billingMonth = $runDate->copy()->startOfMonth();
        $reminderOffsets = collect($settings->reminder_offsets ?? [0, 2, 4]);

        if (!$reminderOffsets->contains($runDate->day - 1)) {
            return;
        }

        $invoices = Invoice::whereDate('billing_month', $billingMonth->toDateString())
            ->where('status', 'unpaid')
            ->where(function (Builder $builder) use ($runDate) {
                $builder->whereNull('last_reminded_at')
                    ->orWhereDate('last_reminded_at', '<', $runDate->toDateString());
            })
            ->with('user')
            ->get();

        $invoices->each(function (Invoice $invoice) use ($runDate) {
            $invoice->increment('reminder_count');
            $invoice->update(['last_reminded_at' => $runDate->copy()]);
            try {
                $invoice->user?->notify(new InvoiceReminderNotification($invoice));
            } catch (\Throwable $e) {
                Log::warning('Failed to send invoice reminder', [
                    'invoice_id' => $invoice->id,
                    'user_id' => $invoice->user_id,
                    'error' => $e->getMessage(),
                ]);
            }
        });
    }

    public function markPaid(Invoice $invoice, array $payload = []): Invoice
    {
        $invoice->update([
            'payment_status' => 'paid',
            'status' => 'paid',
            'transaction_id' => $payload['transaction_id'] ?? $invoice->transaction_id,
            'paid_at' => $payload['paid_at'] ?? now(),
            'notes' => $payload['notes'] ?? $invoice->notes,
            'payment_method' => $payload['payment_method'] ?? $invoice->payment_method,
        ]);

        try {
            $invoice->user?->notify(new PaymentReceivedNotification($invoice));
        } catch (\Throwable $e) {
            Log::warning('Failed to send payment received notification', [
                'invoice_id' => $invoice->id,
                'user_id' => $invoice->user_id,
                'error' => $e->getMessage(),
            ]);
        }

        $status = $invoice->user?->subscriptionStatus;
        if ($status) {
            $status->state = $status->state === 'suspended_manual' ? 'suspended_manual' : 'active';
            $status->last_payment_date = $invoice->paid_at;
            $status->last_invoice_id = $invoice->id;
            $status->save();
        }

        SuspensionLog::where('user_id', $invoice->user_id)
            ->whereNull('reactivated_at')
            ->latest()
            ->first()?->update(['reactivated_at' => now()]);

        return $invoice;
    }

    public function suspendOverdueUsers(Carbon $runDate): void
    {
        $settings = $this->getSettings();
        $thresholdDate = $runDate->copy()->subDays($settings->grace_period_days);

        $invoices = Invoice::where('status', 'unpaid')
            ->whereDate('due_date', '<', $thresholdDate->toDateString())
            ->with('user')
            ->get();

        $invoices->each(function (Invoice $invoice) use ($runDate) {
            $invoice->update(['status' => 'overdue']);
            $status = $invoice->user?->subscriptionStatus()->firstOrCreate([]);
            if (in_array($status->state, ['suspended_manual'], true)) {
                return;
            }

            $status->state = 'suspended';
            $status->save();

            SuspensionLog::create([
                'user_id' => $invoice->user_id,
                'invoice_id' => $invoice->id,
                'reason' => 'Invoice overdue',
                'suspended_at' => $runDate->copy(),
            ]);

            try {
                $invoice->user?->notify(new SubscriptionSuspendedNotification($invoice));
            } catch (\Throwable $e) {
                Log::warning('Failed to send suspension notification', [
                    'invoice_id' => $invoice->id,
                    'user_id' => $invoice->user_id,
                    'error' => $e->getMessage(),
                ]);
            }
        });
    }

    public function reactivatePaidUsers(): void
    {
        SubscriptionStatus::whereIn('state', ['suspended', 'pending'])
            ->whereHas('lastInvoice', function (Builder $query) {
                $query->where('status', 'paid');
            })
            ->get()
            ->each(function (SubscriptionStatus $status) {
                if ($status->state !== 'suspended_manual') {
                    $status->state = 'active';
                    $status->last_payment_date = $status->lastInvoice?->paid_at;
                    $status->save();
                }
            });
    }
}
