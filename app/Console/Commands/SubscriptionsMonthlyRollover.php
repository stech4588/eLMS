<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Services\SubscriptionService;
use Carbon\Carbon;
use Illuminate\Console\Command;

class SubscriptionsMonthlyRollover extends Command
{
    protected $signature = 'subscriptions:monthly-rollover';

    protected $description = 'Generate monthly invoices, send reminders, and enforce subscription state.';

    public function __construct(protected SubscriptionService $subscriptionService)
    {
        parent::__construct();
    }

    public function handle(): int
    {
        $runDate = Carbon::now()->startOfDay();
        if ($runDate->day === 1) {
            $this->info('Generating monthly invoices...');
            User::activeSubscribers()
                ->with('subscriptionStatus')
                ->chunkById(200, function ($users) use ($runDate) {
                    foreach ($users as $user) {
                        $this->subscriptionService->createMonthlyInvoice($user, $runDate);
                    }
                });
        }

        $this->subscriptionService->sendReminderBatch($runDate);
        $this->subscriptionService->suspendOverdueUsers($runDate);
        $this->subscriptionService->reactivatePaidUsers();

        $this->info('Subscription maintenance completed.');

        return self::SUCCESS;
    }
}

