<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class MetaTag extends Model
{
    use HasFactory;

    protected $table = 'metatags';

    protected $fillable = [
        'page_id',
        'meta_title',
        'meta_description',
        'meta_keywords',
    ];

    public function page()
    {
        return $this->belongsTo(Page::class);
    }
}
