<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CommunityPostAttachment extends Model
{
    use HasFactory;

    protected $fillable = [
        'community_post_id',
        'file_path',
        'file_name',
        'file_type',
    ];

    protected $appends = ['file_url'];

    public function getFileUrlAttribute()
    {
        return asset($this->file_path);
    }
}
