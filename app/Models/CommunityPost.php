<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CommunityPost extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['user_id', 'parent_id', 'content', 'title', 'category', 'links'];
    protected $casts = [
        'links' => 'array',
    ];
    protected $appends = ['is_liked', 'likes_count'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function replies()
    {
        return $this->hasMany(CommunityPost::class, 'parent_id');
    }

    public function parent()
    {
        return $this->belongsTo(CommunityPost::class, 'parent_id');
    }

    public function attachments()
    {
        return $this->hasMany(CommunityPostAttachment::class);
    }

    public function likes()
    {
        return $this->hasMany(CommunityPostLike::class);
    }

    public function reads()
    {
        return $this->hasMany(CommunityPostRead::class, 'community_post_id');
    }

    public function poll()
    {
        return $this->hasOne(CommunityPostPoll::class);
    }

    public function getIsLikedAttribute()
    {
        return $this->likes()->where('user_id', auth()->id())->exists();
    }

    public function getLikesCountAttribute()
    {
        return $this->likes()->count();
    }
}
