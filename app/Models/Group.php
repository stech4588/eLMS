<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Group extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'creator_id',
        'profile_picture',
    ];

    protected $appends = ['profile_picture_url'];

    public function getProfilePictureUrlAttribute()
    {
        if ($this->profile_picture) {
            return asset($this->profile_picture);
        }

        return 'https://ui-avatars.com/api/?name=' . urlencode($this->getInitials($this->name)) . '&color=7F9CF5&background=EBF4FF';
    }

    private function getInitials($name)
    {
        $words = explode(' ', $name);
        $initials = '';
        $maxInitials = 2;
        
        foreach ($words as $word) {
            if (count($words) == 1 && strlen($word) > 1) {
                return strtoupper(substr($word, 0, 2));
            }
            if (!empty($word)) {
                $initials .= strtoupper($word[0]);
            }
            if (strlen($initials) >= $maxInitials) {
                break;
            }
        }

        return $initials;
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'creator_id');
    }

    public function members()
    {
        return $this->belongsToMany(User::class, 'group_members')
            ->withPivot('role', 'receive_email_notifications')
            ->withTimestamps();
    }

    public function groupMembers()
    {
        return $this->hasMany(GroupMember::class);
    }

    public function invitations()
    {
        return $this->hasMany(GroupInvitation::class);
    }

    public function messages()
    {
        return $this->hasMany(Message::class);
    }

    public function events()
    {
        return $this->hasMany(GroupEvent::class);
    }
}
