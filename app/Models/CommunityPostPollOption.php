<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CommunityPostPollOption extends Model
{
    use HasFactory;

    protected $fillable = ['community_post_poll_id', 'option_text'];

    public function poll()
    {
        return $this->belongsTo(CommunityPostPoll::class, 'community_post_poll_id');
    }

    public function votes()
    {
        return $this->hasMany(CommunityPostPollVote::class);
    }
}
