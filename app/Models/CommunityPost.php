<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CommunityPost extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'parent_id', 'content'];

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
}
