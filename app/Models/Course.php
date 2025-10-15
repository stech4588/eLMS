<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

class Course extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'title',
        'description',
        'price',
        'category_id',
        'instructor_id',
        'thumbnail',
        'status',
        'certificate_id',
        'industry_id',
        'course_type_id',
        'additional_description',
        'recomendations',
        'topic_id',
    ];

    protected $appends = ['is_favorited'];

    /**
     * Get the user (author) that owns the course.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Get the videos for the course.
     */
    public function videos(): HasMany
    {
        return $this->hasMany(Video::class)->orderBy('order');
    }

    /**
     * Get the course type of the course.
     */
    public function courseType(): BelongsTo
    {
        return $this->belongsTo(CourseType::class);
    }

    /**
     * Get the topic of the course.
     */
    public function topic(): BelongsTo
    {
        return $this->belongsTo(Topic::class);
    }

    /**
     * Get the industry of the course.
     */
    public function industry(): BelongsTo
    {
        return $this->belongsTo(CourseIndustry::class);
    }

    /**
     * Get the certificate associated with the course.
     */
    public function certificate(): BelongsTo
    {
        return $this->belongsTo(CourseCertificate::class);
    }

    /**
     * Get the comments for the course.
     */
    public function comments()
    {
        return $this->hasMany(CourseComment::class)->with('user')->latest();
    }

    /**
     * Get the progress records for the course through its videos.
     */
    public function progresses(): HasManyThrough
    {
        return $this->hasManyThrough(Progress::class, Video::class);
    }

    /**
     * Get the reviews for the course.
     */
    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class);
    }

    /**
     * Get the course favorites records.
     */
    public function favorites(): HasMany
    {
        return $this->hasMany(CourseFavorite::class);
    }

    /**
     * The users that have favorited this course.
     */
    public function favoritedByUsers(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'course_favorites', 'course_id', 'user_id')->withTimestamps();
    }

    /**
     * Check if the course is favorited by the current authenticated user.
     *
     * @return bool
     */
    public function getIsFavoritedAttribute(): bool
    {
        if (!Auth::check()) {
            return false;
        }
        return $this->favorites()->where('user_id', Auth::id())->exists();
    }

    /**
     * Get the progress records for the course through its videos.
     */
    public function progress(): HasMany
    {
        return $this->hasMany(Progress::class);
    }
}
