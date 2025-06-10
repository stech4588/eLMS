<?php
namespace App\Services;

use App\Models\Progress;

class ProgressService
{
    public function getAll() {
        return Progress::all();
    }

    public function getById(int $id) {
        return Progress::findOrFail($id);
    }

    public function updateOrCreateProgress(array $data) {
        // Check if the progress is being marked as completed
        if (isset($data['completed']) && $data['completed'] === true) {
            // If completed, delete the progress record
            $deletedCount = Progress::where('user_id', $data['user_id'])
                                  ->where('video_id', $data['video_id'])
                                  ->delete();
            // Return null or a message indicating deletion upon completion
            // The controller might need to adjust its response based on this
            return $deletedCount > 0 ? ['message' => 'Progress deleted upon completion.', 'deleted' => true] : null;
        } else {
            // If not completed, proceed with the existing update or create logic
            $progress = Progress::firstOrNew(
                [
                    'user_id' => $data['user_id'], 
                    'video_id' => $data['video_id']
                ]
            );

            // Conditionally update watched_duration
            if ($progress->exists) {
                // Only update if new duration is greater
                if (isset($data['watched_duration']) && $data['watched_duration'] > $progress->watched_duration) {
                    $progress->watched_duration = $data['watched_duration'];
                }
            } else {
                // For new records, set the watched_duration
                $progress->watched_duration = $data['watched_duration'] ?? 0; // Default to 0 if not set
            }

            // Always update completed status (should be false here) and last_watched_at from the new data
            if (isset($data['completed'])) {
                $progress->completed = $data['completed']; // Will be false or not set if we are in this block
            }
            if (isset($data['last_watched_at'])) {
                $progress->last_watched_at = $data['last_watched_at'];
            }
            
            $progress->save();
            return $progress;
        }
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
}
