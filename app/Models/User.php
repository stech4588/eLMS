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
        // 'profile_picture',
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
            // Assuming profile_picture stores a relative path to a publicly accessible disk
            // If it stores a full URL, just return $this->profile_picture
            // If it's stored in a private disk and needs a temporary URL, use Storage::temporaryUrl()
            if (str_starts_with($this->profile_picture, 'http')) {
                 return $this->profile_picture;
            }
            return Storage::disk('public')->url($this->profile_picture);
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
