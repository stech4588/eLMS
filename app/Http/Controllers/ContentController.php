<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Course;
use App\Models\CourseType; // For potential future use, if needed for filters etc.

class ContentController extends Controller
{
    /**
     * Display the content page.
     *
     * @return \Inertia\Response
     */
    public function index()
    {
        // Fetch first 3 courses for "Skills you Follow" section on content page
        $skillBasedCourses = Course::with(['courseType', 'videos' => function ($query) {
            $query->orderBy('order', 'asc');
        }, 'user'])
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
                    'author' => $course->user ? $course->user->name : 'Placeholder Author' // Get author name from user relationship
                ];
            });
        
        return Inertia::render('content/mycontent', [
            'skillBasedCourses' => $skillBasedCourses,
            // Pass other data if/when needed for this page, e.g.:
            // 'topics' => \App\Models\Topic::all(), 
            // 'courseTypesData' => CourseType::all(), 
        ]);
    }
}
