<?php

namespace App\Notifications;

use App\Models\Invoice;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class InvoiceReminderNotification extends Notification implements ShouldQueue
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
            ->subject('Reminder: Invoice due on '.$this->invoice->due_date?->format('M d'))
            ->line('This is a friendly reminder that your invoice is still unpaid.')
            ->line('Amount due: $'.number_format($this->invoice->amount, 2))
            ->line('Due date: '.$this->invoice->due_date?->format('M d, Y'))
            ->action('Pay Invoice', route('invoices.show', $this->invoice))
            ->line('Failure to pay by the due date may result in account suspension.');
    }
}

