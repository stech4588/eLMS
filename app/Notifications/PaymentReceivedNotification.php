<?php

namespace App\Notifications;

use App\Models\Invoice;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PaymentReceivedNotification extends Notification implements ShouldQueue
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
            ->subject('Payment Received - Thank You!')
            ->line('We have received your payment for '.$this->invoice->billing_month?->format('F Y').'.')
            ->line('Transaction ID: '.$this->invoice->transaction_id)
            ->line('Amount: $'.number_format($this->invoice->amount, 2))
            ->action('View Invoice', route('invoices.show', $this->invoice))
            ->line('Your access has been restored if it was previously suspended.');
    }
}

