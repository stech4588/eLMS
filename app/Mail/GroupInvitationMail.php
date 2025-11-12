<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\Group;
use App\Services\OpenAIService;

class GroupInvitationMail extends Mailable
{
    use Queueable, SerializesModels;

    public $group;
    public $inviterName;
    public $token;
    public $emailBody;

    /**
     * Create a new message instance.
     */
    public function __construct(Group $group, $inviterName, $token)
    {
        $this->group = $group;
        $this->inviterName = $inviterName;
        $this->token = $token;
        $this->emailBody = $this->generateEmailBody();
    }

    private function generateEmailBody()
    {
        $openAIService = new OpenAIService();
        $prompt = "Generate a friendly and concise email body for a group invitation. The group is named '{$this->group->name}'. The invitation is from '{$this->inviterName}'. Keep it under 100 words.";
        
        try {
            return $openAIService->generateText($prompt);
        } catch (\Exception $e) {
            return "You've been invited by {$this->inviterName} to join the group: {$this->group->name}. Click the link to accept!";
        }
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $invitationLink = url(route('groups.acceptInvite', ['token' => $this->token]));

        return $this->subject('You\'re Invited to Join ' . $this->group->name)
                    ->markdown('emails.group_invitation', ['invitationLink' => $invitationLink]);
    }
}
