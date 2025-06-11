<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Video extends Model
{
    use HasFactory; // Optional: if you use factories

    protected $fillable = [
        'course_id',
        'title',
        'description',
        'video_url',
        'thumbnail_url',
        'duration',
        'order',
    ];

    /**
     * Get the course that owns the video.
     */
    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }

    /**
     * Get the progress entries for the video.
     */
    public function progressEntries(): HasMany
    {
        return $this->hasMany(Progress::class);
    }
}
