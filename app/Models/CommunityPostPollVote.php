<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CommunityPostPollVote extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'community_post_poll_id', 'community_post_poll_option_id'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function poll()
    {
        return $this->belongsTo(CommunityPostPoll::class, 'community_post_poll_id');
    }

    public function option()
    {
        return $this->belongsTo(CommunityPostPollOption::class, 'community_post_poll_option_id');
    }
}
