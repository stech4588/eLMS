<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SubscriptionSetting extends Model
{
    protected $fillable = [
        'monthly_due_day',
        'reminder_offsets',
        'grace_period_days',
    ];

    protected $casts = [
        'reminder_offsets' => 'array',
    ];
}

