<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GroupInvitation extends Model
{
    use HasFactory;

    protected $fillable = [
        'group_id',
        'email',
        'token',
        'status',
    ];

    public function group()
    {
        return $this->belongsTo(Group::class);
    }
}
