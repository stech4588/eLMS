<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Video extends Model
{
    protected $fillable = [
        'course_id',
        'title',
        'description',
        'video_url',
        'thumbnail_url',
        'duration',
        'order',
    ];
}
