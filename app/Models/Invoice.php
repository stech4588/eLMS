<?php

namespace App\Models;

use App\Models\InvoiceDetail;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    protected $fillable = ['user_id', 'amount', 'payment_method', 'payment_status', 'transaction_id', 'paid_at'];

    public function details()
    {
        return $this->hasMany(InvoiceDetail::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

   
}
