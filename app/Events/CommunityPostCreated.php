<?php

namespace App\Events;

use App\Models\CommunityPost;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class CommunityPostCreated implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public CommunityPost $post;

    public function __construct(CommunityPost $post)
    {
        // Ensure relationships are loaded for broadcasting payload
        $this->post = $post->load(['user:id,name,type,profile_picture', 'attachments']);
    }

    public function broadcastOn(): array
    {
        // Single global community channel; extend with IDs if needed
        return [new PrivateChannel('community')];
    }

    public function broadcastAs(): string
    {
        return 'CommunityPostCreated';
    }

    public function broadcastWith(): array
    {
        return [
            'id' => $this->post->id,
            'user_id' => $this->post->user_id,
            'parent_id' => $this->post->parent_id,
            'content' => $this->post->content,
            'created_at' => optional($this->post->created_at)->toISOString(),
            'user' => [
                'id' => $this->post->user->id,
                'name' => $this->post->user->name,
                'type' => $this->post->user->type,
                'profile_photo_url' => $this->post->user->profile_photo_url,
            ],
            'attachments' => $this->post->attachments->map(fn($a) => [
                'id' => $a->id,
                'file_path' => $a->file_path,
                'file_name' => $a->file_name,
                'file_type' => $a->file_type,
            ])->toArray(),
        ];
    }
}


