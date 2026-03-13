<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CommunityPostPoll extends Model
{
    use HasFactory;

    protected $fillable = ['community_post_id', 'question', 'multiple_choice', 'ends_at'];

    protected $appends = ['user_voted_option_ids', 'total_votes'];

    protected $casts = [
        'multiple_choice' => 'boolean',
        'ends_at' => 'datetime',
    ];

    public function getUserVotedOptionIdsAttribute()
    {
        if (!auth()->check()) return [];
        return $this->votes()->where('user_id', auth()->id())->pluck('community_post_poll_option_id')->toArray();
    }

    public function getTotalVotesAttribute()
    {
        return $this->votes()->count();
    }

    public function post()
    {
        return $this->belongsTo(CommunityPost::class, 'community_post_id');
    }

    public function options()
    {
        return $this->hasMany(CommunityPostPollOption::class);
    }

    public function votes()
    {
        return $this->hasMany(CommunityPostPollVote::class);
    }
}
