<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\CourseType;
use App\Models\Topic;
use App\Models\CourseCertificate;
use App\Models\CourseIndustry;
use App\Models\Course;
use App\Models\Enrollment;
use App\Models\Invoice;
use App\Models\Progress;
use Illuminate\Support\Facades\Auth;
// No longer using Storage facade directly here for URL generation, asset() is used.

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        // Fetch first 3 courses for "Skills you Follow"
        $coursesQuery = Course::with(['courseType', 'videos' => function ($query) {
            $query->orderBy('order', 'asc');
        }, 'user']) // Eager load the user relationship
            ->latest();

        if ($request->filled('search')) {
            $search = $request->input('search');
            $coursesQuery->where(function ($query) use ($search) {
                $query->where('title', 'LIKE', "%{$search}%")
                    ->orWhere('description', 'LIKE', "%{$search}%")
                    ->orWhereHas('user', function ($q) use ($search) {
                        $q->where('name', 'LIKE', "%{$search}%");
                    });
            });
        }

        if ($request->filled('topics')) {
            $coursesQuery->whereIn('topic_id', $request->input('topics'));
        }

        if ($request->filled('course_types')) {
            $coursesQuery->whereIn('course_type_id', $request->input('course_types'));
        }

        if ($request->filled('certificates')) {
            $coursesQuery->whereIn('certificate_id', $request->input('certificates'));
        }

        if ($request->filled('course_industries')) {
            $coursesQuery->whereIn('industry_id', $request->input('course_industries'));
        }
            
        $skillBasedCourses = $coursesQuery->get()->map(function ($course) {
                $firstVideo = $course->videos->first();
                $firstVideoThumbnailUrl = null;
                if ($firstVideo && $firstVideo->thumbnail_url) {
                    $firstVideoThumbnailUrl = asset($firstVideo->thumbnail_url);
                }

                $isPurchased = false;
                $progress = 0;
                if (Auth::check()) {
                    $userId = Auth::id();
                    $isPurchased = Invoice::where('user_id', $userId)
                        ->where('payment_status', 'paid')
                        ->whereHas('details', function ($query) use ($course) {
                            $query->where('course_id', $course->id);
                        })
                        ->exists();

                    if ($firstVideo) {
                        $videoProgress = Progress::where('user_id', $userId)
                            ->where('video_id', $firstVideo->id)
                            ->first();

                        if ($videoProgress && $firstVideo->duration > 0) {
                            if ($videoProgress->completed) {
                                $progress = 100;
                            } else {
                                $progress = ($videoProgress->watched_duration / $firstVideo->duration) * 100;
                            }
                        }
                    }
                }

                return [
                    'id' => $course->id,
                    'title' => $course->title,
                    'description' => $course->description,
                    'type' => $course->courseType ? $course->courseType->name : 'N/A',
                    'course_type_id' => $course->courseType ? $course->courseType->id : null,
                    'topic_id' => $course->topic_id,
                    'certificate_id' => $course->certificate_id,
                    'industry_id' => $course->industry_id,
                    'first_video_thumbnail_url' => $firstVideoThumbnailUrl,
                    'first_video_id' => $firstVideo ? $firstVideo->id : null,
                    'is_purchased' => $isPurchased,
                    'price' => $course->price,
                    // Using a placeholder for author as it's not directly on the course model here
                    'author' => $course->user ? $course->user->name : 'Placeholder Author', // Get author name from user relationship
                    'is_favorited' => $course->is_favorited, // Explicitly include is_favorited
                    'progress' => $progress,
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
                    'author' => $course->user ? $course->user->name : 'Placeholder Author', // Get author name from user relationship
                    'is_favorited' => $course->is_favorited, // Explicitly include is_favorited
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
