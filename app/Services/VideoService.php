<?php

namespace App\Services;

use App\Models\Video;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Log;

class VideoService
{
    public function getAll(): Collection
    {
        return Video::all();
    }

    public function getById(int $id): Video
    {
        return Video::findOrFail($id);
    }

    public function create(array $data): Video
    {
        Log::info("VideoService::create called", [
            'data' => $data,
            'course_id' => $data['course_id'] ?? null,
            'has_video_url' => !empty($data['video_url'] ?? null),
            'has_thumbnail_url' => !empty($data['thumbnail_url'] ?? null)
        ]);
        
        try {
            $video = Video::create($data);
            
            Log::info("VideoService::create - Video model created", [
                'video_id' => $video->id,
                'video_url' => $video->video_url,
                'title' => $video->title,
                'course_id' => $video->course_id,
                'order' => $video->order,
                'created_at' => $video->created_at
            ]);
            
            // Verify it was actually saved
            $verifyVideo = Video::find($video->id);
            if ($verifyVideo) {
                Log::info("VideoService::create - Video verified in database", [
                    'video_id' => $verifyVideo->id,
                    'video_url' => $verifyVideo->video_url
                ]);
            } else {
                Log::error("VideoService::create - Video NOT found in database after creation!", [
                    'expected_id' => $video->id,
                    'data' => $data
                ]);
            }
            
            return $video;
        } catch (\Throwable $e) {
            Log::error("VideoService::create - Exception occurred", [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'data' => $data
            ]);
            throw $e;
        }
    }

    public function update(int $id, array $data): Video
    {
        Log::info("VideoService::update called", [
            'video_id' => $id,
            'update_data' => $data
        ]);
        
        try {
            $video = Video::findOrFail($id);
            
            Log::info("VideoService::update - Video found", [
                'video_id' => $video->id,
                'current_video_url' => $video->video_url,
                'current_title' => $video->title
            ]);
            
            $video->update($data);
            
            Log::info("VideoService::update - Video model updated", [
                'video_id' => $video->id,
                'updated_video_url' => $video->video_url,
                'updated_title' => $video->title
            ]);
            
            // Refresh and verify
            $video->refresh();
            $verifyVideo = Video::find($id);
            if ($verifyVideo) {
                Log::info("VideoService::update - Video verified in database", [
                    'video_id' => $verifyVideo->id,
                    'video_url' => $verifyVideo->video_url,
                    'title' => $verifyVideo->title
                ]);
            } else {
                Log::error("VideoService::update - Video NOT found in database after update!", [
                    'video_id' => $id
                ]);
            }
            
            return $video;
        } catch (\Throwable $e) {
            Log::error("VideoService::update - Exception occurred", [
                'video_id' => $id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'data' => $data
            ]);
            throw $e;
        }
    }

    public function delete(int $id): bool
    {
        $video = Video::findOrFail($id);
        return $video->delete();
    }
}
