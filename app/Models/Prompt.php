<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Prompt extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'prompt_text',
        'target_audience',
        'trigger_condition',
        'frequency',
        'times_per_day',
        'last_sent_at',
        'is_active',
    ];
}
