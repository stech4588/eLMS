<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CourseTopic extends Model
{
    use HasFactory;

    // If your table name is different from 'course_topics',
    // uncomment and set the following line:
    protected $table = 'topics';

    // Define the fillable attributes for mass assignment
    protected $fillable = [
        'name', 
    ];

    /**
     * Get the courses associated with this topic.
     */
    public function courses()
    {
        return $this->hasMany(Course::class, 'topic_id');
    }
} 