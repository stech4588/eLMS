<?php
namespace App\Services;

use App\Models\Progress;
use App\Models\User;
use App\Models\Course;
use App\Models\Video;
use App\Models\Review;
use Illuminate\Support\Facades\Log;
use App\Events\CourseCompleted;

class ProgressService
{
    public function getAll() {
        return Progress::all();
    }

    public function getById(int $id) {
        return Progress::findOrFail($id);
    }

    public function updateOrCreateProgress(array $data)
    {
        // Find or create the progress record
        $progress = Progress::firstOrNew(
            [
                'user_id' => $data['user_id'],
                'video_id' => $data['video_id']
            ]
        );

        // Update watched_duration if it's greater or if it's a new record
        if (isset($data['watched_duration'])) {
            if (!$progress->exists || $data['watched_duration'] > $progress->watched_duration) {
                $progress->watched_duration = $data['watched_duration'];
            }
        }

        // Always update completed status and last_watched_at from the new data
        if (isset($data['completed'])) {
            $progress->completed = $data['completed'];
        }
        if (isset($data['last_watched_at'])) {
            $progress->last_watched_at = $data['last_watched_at'];
        }

        $progress->save();

        $courseCompleted = false;
        $video = Video::with('course')->find($data['video_id']);
        $user = User::find($data['user_id']);

        if ($video && $video->course && $user) {
            $course = $video->course;
            $progressPercentage = $this->getOverallCourseProgress($user, $course);

            if ($progressPercentage >= 100) {
                $alreadyReviewed = Review::where('user_id', $user->id)
                                         ->where('course_id', $course->id)
                                         ->exists();

                if (!$alreadyReviewed) {
                    $courseCompleted = true;
                    // Fire completion event
                    event(new CourseCompleted($user, $course));
                }
            }
        }

        return [
            'progress' => $progress,
            'course_completed' => $courseCompleted,
        ];
    }

    public function update(int $id, array $data) {
        $progress = Progress::findOrFail($id);
        $progress->update($data);
        return $progress;
    }

    public function delete(int $id): bool {
        return Progress::findOrFail($id)->delete();
    }

    /**
     * Get progress for a specific user and video.
     *
     * @param int $userId
     * @param int $videoId
     * @return \App\Models\Progress|null
     */
    public function getProgressForUserAndVideo(int $userId, int $videoId): ?Progress
    {
        return Progress::where('user_id', $userId)
                       ->where('video_id', $videoId)
                       ->first();
    }

    public function getProgressForUserAndCourse(User $user, Course $course)
    {
        $videoIds = $course->videos->pluck('id');
        return Progress::where('user_id', $user->id)
                       ->whereIn('video_id', $videoIds)
                       ->get();
    }

    public function isCourseCompletedAndUnreviewed(User $user, Course $course): bool
    {
        $progressPercentage = $this->getOverallCourseProgress($user, $course);

        if ($progressPercentage < 100) {
            return false;
        }

        $alreadyReviewed = Review::where('user_id', $user->id)
                                 ->where('course_id', $course->id)
                                 ->exists();

        return !$alreadyReviewed;
    }

    /**
     * Calculate overall course progress for a user.
     *
     * @param \App\Models\User $user
     * @param \App\Models\Course $course
     * @return float The overall progress percentage (0-100).
     */
    public function getOverallCourseProgress(User $user, Course $course): float
    {
        $totalCourseDuration = $course->videos->sum('duration');

        if ($totalCourseDuration === 0) {
            return 0.0; // Avoid division by zero, or handle courses without videos
        }

        $totalWatchedDuration = 0;
        foreach ($course->videos as $video) {
            $progress = Progress::where('user_id', $user->id)
                                ->where('video_id', $video->id)
                                ->first();
            
            if ($progress && $progress->completed) {
                $totalWatchedDuration += $video->duration; // If completed, count full duration
            } elseif ($progress) {
                $totalWatchedDuration += $progress->watched_duration; // Add watched duration
            } else {
                // No progress record yet
            }
        }

        $progressPercentage = min(100.0, ($totalWatchedDuration / $totalCourseDuration) * 100);

        // If the course is 100% complete and it hasn't been marked as completed before (optional, to prevent multiple emails)
        if ($progressPercentage >= 100) {
            $alreadyCompleted = Review::where('user_id', $user->id)
                                        ->where('course_id', $course->id)
                                        ->exists();
            if (!$alreadyCompleted) {
                // event(new CourseCompleted($user, $course));
            }
        }

        return $progressPercentage;
    }
}
