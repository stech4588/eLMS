<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SuspensionLog extends Model
{
    protected $fillable = [
        'user_id',
        'invoice_id',
        'reason',
        'suspended_at',
        'reactivated_at',
    ];

    protected $casts = [
        'suspended_at' => 'datetime',
        'reactivated_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function invoice(): BelongsTo
    {
        return $this->belongsTo(Invoice::class);
    }
}

