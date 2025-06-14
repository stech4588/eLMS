<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'phone_number',
        'role_id',
        'type',
        'primary_learning_goal',
        'preferred_topic_ids',
        'resume_path',
        'profile_picture',
        // 'bio',
        // 'type',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be appended to the model's array form.
     *
     * @var array<int, string>
     */
    protected $appends = [
        'profile_photo_url',
    ];

    /**
     * Get the URL to the user's profile photo.
     *
     * @return string|null
     */
    public function getProfilePhotoUrlAttribute(): ?string
    {
        if ($this->profile_picture) {
            if (str_starts_with($this->profile_picture, 'http')) {
                 return $this->profile_picture;
            }
            return asset($this->profile_picture);
        }
        return null; // Fallback will be handled by the frontend
    }

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'preferred_topic_ids' => 'array',
        ];
    }

    /**
     * Get the user's course favorites.
     */
    public function courseFavorites(): HasMany
    {
        return $this->hasMany(CourseFavorite::class);
    }

    /**
     * The courses that the user has favorited.
     */
    public function favoriteCourses(): BelongsToMany
    {
        return $this->belongsToMany(Course::class, 'course_favorites', 'user_id', 'course_id')->withTimestamps();
    }

    /**
     * Get the progress entries for the user.
     */
    public function progressEntries(): HasMany
    {
        return $this->hasMany(Progress::class);
    }
}
