<?php

namespace App\Mail;

use App\Models\Course;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Support\Str;

class CourseCompletionEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $course;

    /**
     * Create a new message instance.
     */
    public function __construct(User $user, Course $course)
    {
        $this->user = $user;
        $this->course = $course;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Congratulations on Completing Your Course!',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        // Generate a simple referral code from user id (base36) so no DB changes needed
        $referralCode = strtoupper(base_convert($this->user->id, 10, 36));
        $referralUrl = route('register.complete', ['ref' => $referralCode]);

        return new Content(
            view: 'emails.course-completion',
            with: [
                'userName' => $this->user->name,
                'courseTitle' => $this->course->title,
                'referralUrl' => $referralUrl,
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
        return [
            Attachment::fromPath(public_path('images/MBM_Uni.png'))
                        ->as('MBM_Uni.png')
                        ->withMime('image/png'),
        ];
    }
}
