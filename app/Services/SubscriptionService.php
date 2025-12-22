<?php

namespace App\Services;

use App\Models\Invoice;
use App\Models\User;
use Carbon\Carbon;

class SubscriptionService
{
    public function __construct(
        protected InvoiceService $invoiceService
    ) {
    }

    public function createMonthlyInvoice(User $user, ?Carbon $billingDate = null, array $overrides = []): ?Invoice
    {
        return $this->invoiceService->createMonthlyInvoice($user, $billingDate, $overrides);
    }

    public function sendReminderBatch(Carbon $runDate): void
    {
        $this->invoiceService->sendReminderBatch($runDate);
    }

    public function suspendOverdueUsers(Carbon $runDate): void
    {
        $this->invoiceService->suspendOverdueUsers($runDate);
    }

    public function reactivatePaidUsers(): void
    {
        $this->invoiceService->reactivatePaidUsers();
    }

    public function markPaid(Invoice $invoice, array $payload = []): Invoice
    {
        return $this->invoiceService->markPaid($invoice, $payload);
    }
}

