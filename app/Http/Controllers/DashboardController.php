<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\CourseType;
use App\Models\Topic;
use App\Models\CourseCertificate;
use App\Models\CourseIndustry;
use App\Models\Course;
// No longer using Storage facade directly here for URL generation, asset() is used.

class DashboardController extends Controller
{
    public function index()
    {
        // Fetch first 3 courses for "Skills you Follow"
        $skillBasedCourses = Course::with(['courseType', 'videos' => function ($query) {
            $query->orderBy('order', 'asc');
        }, 'user']) // Eager load the user relationship
            ->latest()
            
            ->get()
            ->map(function ($course) {
                $firstVideoThumbnailUrl = null;
                if ($course->videos->isNotEmpty() && $course->videos->first()->thumbnail_url) {
                    $firstVideoThumbnailUrl = asset($course->videos->first()->thumbnail_url);
                }
                return [
                    'id' => $course->id,
                    'title' => $course->title,
                    'type' => $course->courseType ? $course->courseType->name : 'N/A',
                    'course_type_id' => $course->courseType ? $course->courseType->id : null,
                    'topic_id' => $course->topic_id,
                    'certificate_id' => $course->certificate_id,
                    'industry_id' => $course->industry_id,
                    'first_video_thumbnail_url' => $firstVideoThumbnailUrl,
                    // Using a placeholder for author as it's not directly on the course model here
                    'author' => $course->user ? $course->user->name : 'Placeholder Author' // Get author name from user relationship
                ];
            });
            //dd($skillBasedCourses);
        // Fetch all courses for "New Releases", ordered by latest
        $allNewReleaseCourses = Course::with(['courseType', 'videos' => function ($query) {
            $query->orderBy('order', 'asc');
        }, 'user']) // Eager load the user relationship
            ->latest()
            ->get()
            ->map(function ($course) {
                $firstVideoThumbnailUrl = null;
                if ($course->videos->isNotEmpty() && $course->videos->first()->thumbnail_url) {
                    $firstVideoThumbnailUrl = asset($course->videos->first()->thumbnail_url);
                }
                return [
                    'id' => $course->id,
                    'title' => $course->title,
                    'type' => $course->courseType ? $course->courseType->name : 'N/A',
                    'first_video_thumbnail_url' => $firstVideoThumbnailUrl,
                    // Using a placeholder for author
                    'author' => $course->user ? $course->user->name : 'Placeholder Author' // Get author name from user relationship
                ];
            });

        //dd($skillBasedCourses);

        return Inertia::render('Dashboard', [
            'courseTypes' => CourseType::all(),
            'topics' => Topic::all(),
            'courseCertificates' => CourseCertificate::all(),
            'courseIndustries' => CourseIndustry::all(),
            'skillBasedCourses' => $skillBasedCourses,
            'allNewReleaseCourses' => $allNewReleaseCourses, // Added new prop for all new releases
        ]);
    }
}
