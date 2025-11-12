<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Pricing extends Model
{
    use HasFactory;
    protected $fillable = [
        'plan_name',
        'slug',
        'description',
        'price',
        'type'
    ];
}
