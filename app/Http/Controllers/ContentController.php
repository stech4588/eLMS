<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Course;
use App\Models\Topic;
use App\Models\CourseType; // For potential future use, if needed for filters etc.
use Inertia\Response as InertiaResponse;
use Illuminate\Support\Facades\Auth; // Auth facade can be used, but $request->user() is preferred
use App\Models\Progress;
use App\Models\User; // Needed for type hinting $user and its relationships
use App\Models\Invoice;
// Assuming App\Models\Course is already created/updated with necessary relationships
// Assuming App\Models\Video is already created/updated with necessary relationships
use Carbon\Carbon;

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
                    'type' => $course->courseType ? $course->courseType->name : 'N/A',
                    'first_video_thumbnail_url' => $firstVideoThumbnailUrl,
                    'author' => $course->user ? $course->user->name : 'Placeholder Author', // Get author name from user relationship
                    'is_favorited' => $course->is_favorited, // Explicitly include is_favorited
                    'is_purchased' => $isPurchased,
                    'first_video_id' => $course->videos->isNotEmpty() ? $course->videos->first()->id : null,
                    'progress' => $progress,
                ];
            });
        
             $topics = Topic::all();
             
        return Inertia::render('content/mycontent', [
            'skillBasedCourses' => $skillBasedCourses,
            'topics' => $topics,
            // Pass other data if/when needed for this page, e.g.:
            // 'topics' => \App\Models\Topic::all(), 
            // 'courseTypesData' => CourseType::all(), 
        ]);
    }

    /**
     * Display the user's library page with in-progress and saved items.
     *
     * @param Request $request
     * @return InertiaResponse
     */
    public function mylibrary(Request $request): InertiaResponse
    {
        $user = $request->user();

        if (!$user) {
            // This should ideally be caught by the 'auth' middleware
            // Redirecting to login if no user is found (though middleware should handle this)
            return redirect()->route('login');
        }

        // Fetch In-Progress Video Items
        $inProgressItems = Progress::where('user_id', $user->id)
            ->where('completed', false)
            ->with([
                'video' => function ($query) {
                    $query->select('id', 'course_id', 'title', 'thumbnail_url', 'duration');
                },
                'video.course' => function ($query) {
                    $query->select('id', 'user_id', 'title', 'course_type_id'); // Ensure course_type_id is selected for the relationship
                },
                'video.course.user' => function($query){
                    $query->select('id', 'name'); // Author of the course
                },
                'video.course.courseType' => function($query){ // Eager load courseType for the course
                    $query->select('id', 'name');
                }
            ])
            ->whereHas('video.course') // Ensures video and its course exist
            ->latest('last_watched_at')
            ->get()
            ->map(function ($progressEntry) {
                if (!$progressEntry->video || !$progressEntry->video->course) {
                    return null; // Skip if essential data is missing
                }

                $video = $progressEntry->video;
                $course = $video->course;
                $author = $course->user;

                $progressPercentage = 0;
                $timeLeftFormatted = 'N/A';
                $durationFormatted = 'N/A';

                if (isset($video->duration) && $video->duration > 0) {
                    $durationFormatted = $this->formatDuration($video->duration);
                    $watchedDuration = $progressEntry->watched_duration ?? 0;
                    $progressPercentage = round(($watchedDuration / $video->duration) * 100);
                    $remainingSeconds = $video->duration - $watchedDuration;
                    if ($remainingSeconds < 0) $remainingSeconds = 0;
                    $timeLeftFormatted = $this->formatDuration($remainingSeconds) . ' left';
                } elseif (isset($video->duration) && $video->duration === 0) {
                    // E.g., for an article or a very short clip marked as having 0 duration
                    $durationFormatted = '0s';
                    $progressPercentage = ($progressEntry->watched_duration > 0 || $progressEntry->completed) ? 100 : 0;
                    $timeLeftFormatted = ($progressPercentage === 100) ? 'Completed' : '0s left';
                }

                return [
                    'id' => $progressEntry->id, // Unique key for v-for (progress_id)
                    'video_id' => $video->id,
                    'type' => ($course->courseType && $course->courseType->name) ? $course->courseType->name : 'Video', // New: Use course's type, fallback to 'Video'
                    'title' => $video->title, // Video Title
                    'course_title' => $course->title, // Course Title
                    'author' => $author ? $author->name : 'N/A', // Course Author Name
                    'updated' => $progressEntry->last_watched_at ? Carbon::parse($progressEntry->last_watched_at)->isoFormat('MMMM YYYY') : 'N/A',
                    'progress' => $progressPercentage,
                    'timeLeft' => $timeLeftFormatted,
                    'thumbnail' => $video->thumbnail_url ? asset($video->thumbnail_url) : '/images/skill_section_thumbnail.svg', // Default thumbnail
                    'duration' => $durationFormatted, // Video duration formatted
                    'course_id' => $course->id, // For navigation
                ];
            })->filter()->values();

        // Fetch Saved Courses
        // Assumes User model has favoriteCourses() relationship
        $savedCoursesRaw = $user->favoriteCourses()->with([
            'user' => function($query) { $query->select('id', 'name'); }, // Author
            'courseType' => function($query) { $query->select('id', 'name'); },
            'videos' => function ($query) { // For thumbnail and duration calculation
                $query->select('id', 'course_id', 'thumbnail_url', 'duration')->orderBy('order', 'asc');
            }
        ])->get();

        $savedCourses = $savedCoursesRaw->map(function ($course) {
            $totalDurationSeconds = $course->videos->sum('duration');
            $durationFormatted = $totalDurationSeconds > 0 ? $this->formatDuration($totalDurationSeconds) : 'N/A';

            $thumbnail = '/images/skill_section_thumbnail.svg'; // Default
            if ($course->thumbnail) {
                $thumbnail = asset($course->thumbnail); // Course's own thumbnail
            } elseif ($course->videos->isNotEmpty() && $course->videos->first()->thumbnail_url) {
                $thumbnail = asset($course->videos->first()->thumbnail_url); // First video's thumbnail
            }

            return [
                'id' => $course->id,
                'type' => ($course->courseType && $course->courseType->name) ? $course->courseType->name : 'Course',
                'title' => $course->title,
                'author' => $course->user ? $course->user->name : 'N/A',
                'updated' => $course->updated_at ? $course->updated_at->isoFormat('MMMM YYYY') : 'N/A',
                'thumbnail' => $thumbnail,
                'duration' => $durationFormatted,
                // Add other fields if your 'Saved' view requires them differently
            ];
        });

        return Inertia::render('library/mylibrary', [
            'savedCourses' => $savedCourses,
            'inProgressItems' => $inProgressItems,
        ]);
    }

    /**
     * Helper function to format duration from seconds to a readable string (e.g., 1h 15m or 45m 30s).
     *
     * @param int $totalSeconds
     * @return string
     */
    private function formatDuration(int $totalSeconds): string
    {
        if ($totalSeconds <= 0) return '0s';

        $hours = floor($totalSeconds / 3600);
        $minutes = floor(($totalSeconds % 3600) / 60);
        $seconds = $totalSeconds % 60;

        $durationParts = [];
        if ($hours > 0) {
            $durationParts[] = "{$hours}h";
        }
        if ($minutes > 0 || $hours > 0) { // Show minutes if hours are present, or if minutes > 0
            $durationParts[] = "{$minutes}m";
        }
        if ($seconds > 0 && $hours == 0) { // Only show seconds if no hours
             $durationParts[] = "{$seconds}s";
        }
        if (empty($durationParts) && $totalSeconds > 0 && $hours == 0 && $minutes == 0){
             $durationParts[] = "{$totalSeconds}s"; // e.g. for 0m 30s, show 30s
        } else if (empty($durationParts)) {
            return '0s'; // Fallback for 0 duration after processing
        }

        return implode(' ', $durationParts);
    }

    // The index() method that was causing redeclaration has been removed from this edit.
    // If an index() method is required for the '/content' route, ensure it exists uniquely in your class.
}
