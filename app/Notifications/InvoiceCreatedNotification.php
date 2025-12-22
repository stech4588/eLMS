<?php

namespace App\Notifications;

use App\Models\Invoice;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class InvoiceCreatedNotification extends Notification implements ShouldQueue
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
            ->subject('New Invoice for '.$this->invoice->billing_month?->format('F Y'))
            ->line('A new invoice has been generated for your subscription.')
            ->line('Amount: $'.number_format($this->invoice->amount, 2))
            ->line('Due date: '.$this->invoice->due_date?->format('M d, Y'))
            ->action('View Invoice', route('invoices.show', $this->invoice))
            ->line('Please pay before the due date to keep your access active.');
    }
}

