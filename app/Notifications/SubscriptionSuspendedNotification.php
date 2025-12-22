<?php

namespace App\Notifications;

use App\Models\Invoice;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class SubscriptionSuspendedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(protected Invoice $invoice)
    {
    }

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Account Suspended Due to Unpaid Invoice')
            ->line('Your access has been suspended because the invoice for '.$this->invoice->billing_month?->format('F Y').' remains unpaid.')
            ->line('Please pay the outstanding amount of $'.number_format($this->invoice->amount, 2).' as soon as possible.')
            ->action('Resolve Now', route('invoices.show', $this->invoice))
            ->line('Access will be restored automatically once payment is completed.');
    }
}

