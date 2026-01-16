<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use App\Models\User;
use App\Models\Course;

class CourseReminder extends Mailable
{
    use Queueable, SerializesModels;

    public User $user;
    public array $courses;

    /**
     * Create a new message instance.
     */
    public function __construct(User $user, array $courses)
    {
        $this->user = $user;
        $this->courses = $courses;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Don\'t forget your ElevateU University courses!',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.course-reminder',
            with: [
                'user' => $this->user,
                'courses' => $this->courses,
            ],
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
