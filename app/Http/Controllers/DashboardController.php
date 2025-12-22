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
use App\Services\ProgressService; // Import ProgressService
use Illuminate\Support\Facades\Log; // Import Log

class DashboardController extends Controller
{
    protected ProgressService $progressService; // Declare the service property

    public function __construct(ProgressService $progressService)
    {
        $this->progressService = $progressService;
    }

    public function index(Request $request)
    {
        
        $userId = Auth::id();
        $user = Auth::user(); // Get the full user object for ProgressService
        

        // Fetch first 3 courses for "Skills you Follow" - only published courses
        $coursesQuery = Course::withTrashed()->with([
            'courseType',
            'videos' => function ($query) {
                $query->orderBy('order', 'asc');
            },
            'user'
        ]) // Eager load the user relationship
            ->where('status', 'published') // Only show published courses
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
            
        $skillBasedCourses = $coursesQuery
            ->paginate(9)
            ->withQueryString()
            ->through(function ($course) use ($userId, $user) {
                $firstVideo = $course->videos->first();
                $firstVideoThumbnailUrl = null;
                if ($firstVideo && $firstVideo->thumbnail_url) {
                    $firstVideoThumbnailUrl = asset($firstVideo->thumbnail_url);
                }

                $isPurchased = false;
                $progress = 0;
                if (Auth::check()) {
                    $isPurchased = Invoice::where('user_id', $userId)
                        ->where('payment_status', 'paid')
                        ->whereHas('details', function ($query) use ($course) {
                            $query->where('course_id', $course->id);
                        })
                        ->exists();

                    // Use the ProgressService to get overall course progress
                    if ($user) {
                        $progress = $this->progressService->getOverallCourseProgress($user, $course);
                    } else {
                        // no-op for guests
                    }
                } else {
                    // no-op for guests
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
                    'author' => $course->user ? $course->user->name : 'Placeholder Author',
                    'is_favorited' => $course->is_favorited,
                    'progress' => $progress,
                    'deleted_at' => $course->deleted_at,
                ];
            });

        // Fetch all courses for "New Releases", ordered by latest - only published courses
        $allNewReleaseCourses = Course::withTrashed()->with([
            'courseType',
            'videos' => function ($query) {
                $query->orderBy('order', 'asc');
            },
            'user'
        ])
            ->where('status', 'published') // Only show published courses
            ->latest()
            ->get()
            ->map(function ($course) use ($userId, $user) {
                $firstVideoThumbnailUrl = null;
                if ($course->videos->isNotEmpty() && $course->videos->first()->thumbnail_url) {
                    $firstVideoThumbnailUrl = asset($course->videos->first()->thumbnail_url);
                }
                $progress = 0;
                if ($userId && $user) {
                    $progress = $this->progressService->getOverallCourseProgress($user, $course);
                } else {
                    
                }
                return [
                    'id' => $course->id,
                    'title' => $course->title,
                    'type' => $course->courseType ? $course->courseType->name : 'N/A',
                    'first_video_thumbnail_url' => $firstVideoThumbnailUrl,
                    'author' => $course->user ? $course->user->name : 'Placeholder Author',
                    'is_favorited' => $course->is_favorited,
                    'progress' => $progress, // Include progress for new releases as well
                    'deleted_at' => $course->deleted_at,
                ];
            });

        return Inertia::render('Dashboard', [
            'courseTypes' => CourseType::all(),
            'topics' => Topic::all(),
            'courseCertificates' => CourseCertificate::all(),
            'courseIndustries' => CourseIndustry::all(),
            'skillBasedCourses' => $skillBasedCourses,
            'allNewReleaseCourses' => $allNewReleaseCourses,
        ]);
    }
}
