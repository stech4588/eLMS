<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Mail\Mailables\Attachment;

class PromptGeneratedEmail extends Mailable
{
    use Queueable, SerializesModels;

    public string $emailContent;
    public string $studentName;
    public int $goalHours;
    public float $watchTimeInHours;

    /**
     * Create a new message instance.
     */
    public function __construct(string $emailContent, string $studentName, int $goalHours, float $watchTimeInHours)
    {
        $this->emailContent = $emailContent;
        $this->studentName = $studentName;
        $this->goalHours = $goalHours;
        $this->watchTimeInHours = $watchTimeInHours;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Your Daily Progress Update from ElevateU University',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            markdown: 'emails.prompt-generated',
            with: [
                'content' => $this->emailContent,
                'studentName' => $this->studentName,
                'goalHours' => $this->goalHours,
                'watchTimeInHours' => $this->watchTimeInHours,
            ]
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
