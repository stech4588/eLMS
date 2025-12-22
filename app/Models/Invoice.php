<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Invoice extends Model
{
    protected $fillable = [
        'user_id',
        'amount',
        'plan',
        'billing_cycle',
        'payment_method',
        'payment_status',
        'transaction_id',
        'paid_at',
        'billing_month',
        'due_date',
        'status',
        'notes',
        'reminder_count',
        'last_reminded_at',
    ];

    protected $casts = [
        'paid_at' => 'datetime',
        'billing_month' => 'date',
        'due_date' => 'date',
        'last_reminded_at' => 'datetime',
    ];

    public function details(): HasMany
    {
        return $this->hasMany(InvoiceDetail::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
