<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Promotion extends Model
{
    use HasFactory;
    protected $fillable = ['title', 'promotion_type', 'text_content', 'image_path', 'is_active', 'till_date'];
}
