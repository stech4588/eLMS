<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ContactUsNotification extends Notification
{
    use Queueable;

    protected $contactData;

    /**
     * Create a new notification instance.
     */
    public function __construct(array $contactData)
    {
        $this->contactData = $contactData;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('New Contact Form Submission')
            ->line('You have received a new contact form submission.')
            ->line('From: ' . $this->contactData['first_name'] . ' ' . $this->contactData['last_name'])
            ->line('Email: ' . $this->contactData['email'])
            ->line('Message: ' . $this->contactData['message']);
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'type' => 'contact_form',
            'message' => 'New contact form submission from ' . $this->contactData['first_name'] . ' ' . $this->contactData['last_name'],
            'link' => '#',
            'contact_name' => $this->contactData['first_name'] . ' ' . $this->contactData['last_name'],
            'contact_first_name' => $this->contactData['first_name'],
            'contact_last_name' => $this->contactData['last_name'],
            'contact_email' => $this->contactData['email'],
            'contact_message' => $this->contactData['message'],
        ];
    }
}
