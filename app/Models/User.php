<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Laravel\Sanctum\HasApiTokens;
use App\Models\QuizAttempt;
use App\Models\Invoice;
use App\Models\SubscriptionStatus;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasApiTokens;

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
        'is_active',
        'google_id',
        'apple_id',
        'facebook_id',
        'can_view_community',
        'daily_learning_goal',
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
            'can_view_community' => 'boolean',
        ];
    }

    /**
     * The "booted" method of the model.
     *
     * @return void
     */
    protected static function booted()
    {
        static::created(function ($user) {
            try {
                $defaultSettings = [
                    ['key' => 'receives_new_course_notification_emails', 'value' => true],
                    ['key' => 'receives_course_completion_emails', 'value' => true],
                    ['key' => 'receives_course_reminder_emails', 'value' => true],
                    ['key' => 'receives_new_message_emails', 'value' => true],
                    ['key' => 'receives_promotional_emails', 'value' => false],
                    ['key' => 'receives_wellness_checkin_emails', 'value' => true],
                    ['key' => 'receives_motivational_quote_emails', 'value' => true],
                ];

                foreach ($defaultSettings as $setting) {
                    try {
                        $user->emailNotificationSettings()->create($setting);
                    } catch (\Exception $e) {
                        \Illuminate\Support\Facades\Log::error("Failed to create email notification setting for user {$user->id}: " . $e->getMessage());
                    }
                }

                try {
                    $user->subscriptionStatus()->firstOrCreate([], [
                        'state' => 'pending',
                    ]);
                } catch (\Exception $e) {
                    \Illuminate\Support\Facades\Log::error("Failed to create subscription status for user {$user->id}: " . $e->getMessage());
                }
            } catch (\Exception $e) {
                \Illuminate\Support\Facades\Log::error("Error in User booted method for user {$user->id}: " . $e->getMessage());
            }
        });
    }

    /**
     * Check if the user can receive a specific email notification.
     *
     * @param string $key
     * @return bool
     */
    public function canReceiveEmail(string $key): bool
    {
        $setting = $this->emailNotificationSettings()->where('key', $key)->first();
        return $setting ? $setting->value : false;
    }

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
        return asset('images/user.svg');
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

    /**
     * Get the instructor record associated with the user.
     */
    public function instructor(): HasOne
    {
        return $this->hasOne(Instructor::class);
    }

    public function invoices(): HasMany
    {
        return $this->hasMany(Invoice::class);
    }

    public function subscriptionStatus(): HasOne
    {
        return $this->hasOne(SubscriptionStatus::class);
    }

    public function scopeActiveSubscribers($query)
    {
        return $query->where(function ($builder) {
            $builder->whereHas('subscriptionStatus', function ($q) {
                $q->whereIn('state', ['active', 'pending']);
            })->orDoesntHave('subscriptionStatus');
        });
    }

    /**
     * Get the email notification settings for the user.
     */
    public function emailNotificationSettings(): HasMany
    {
        return $this->hasMany(EmailNotificationSetting::class);
    }

    public function receivesBroadcastNotificationsOn(): string
    {
        return 'users.'.$this->id;
    }

    public function quizAttempts(): HasMany
    {
        return $this->hasMany(QuizAttempt::class);
    }
}
