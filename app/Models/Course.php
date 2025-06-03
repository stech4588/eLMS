<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    protected $fillable = [
        'title',
        'description',
        'price',
        'category_id',
        'instructor_id',
        'thumbnail',
        'status',
        'user_id',
        'certificate_id',
        'industry_id',
        'course_type_id',
        'additional_description',
        'recomendations',
    ];

    public function courseType()
    {
        return $this->belongsTo(CourseType::class, 'course_type_id');
    }

    public function certificate()
    {
        return $this->belongsTo(CourseCertificate::class, 'certificate_id');
    }
    
    public function industry()
    {
        // Make sure 'industry_id' is the correct foreign key column name in your 'courses' table
        // And CourseIndustry::class is the correct model for your industries.
        return $this->belongsTo(CourseIndustry::class, 'industry_id');
    }

    public function videos()
    {
        return $this->hasMany(Video::class)->orderBy('order', 'asc');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the comments for the course.
     */
    public function comments()
    {
        return $this->hasMany(CourseComment::class)->with('user')->latest(); // Eager load user and order by latest
    }
}
