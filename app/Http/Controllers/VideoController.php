<?php

namespace App\Http\Controllers;

use App\Http\Requests\VideoRequest;
use App\Services\VideoService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class VideoController extends Controller
{
    public function __construct(protected VideoService $videoService)
    {}

    public function index(): JsonResponse
    {
        $videos = $this->videoService->getAll();
        return response()->json($videos);
    }

    public function store(Request $request): JsonResponse
    {
        Log::info('Video store request data:', $request->all());

        $validatedData = $request->validate([
            'course_id' => 'required|integer|exists:courses,id',
            'videos' => 'required|array',
            'videos.*.title' => 'required|string|max:255',
            'videos.*.description' => 'required|string',
            'videos.*.videoFile' => 'required|file|mimes:mp4,mov,ogg,qt|max:100000',
            'videos.*.order' => 'required|integer',
        ]);

        Log::info('Validated video data:', $validatedData);

        $createdVideos = [];
        $courseId = $validatedData['course_id'];

        foreach ($validatedData['videos'] as $videoData) {
            $videoPath = null;
            if (isset($videoData['videoFile'])) {
                $folderName = 'course_' . $courseId . '_videos';
                $videoPath = $videoData['videoFile']->store($folderName, 'public');
                Log::info("Video file stored at: {$videoPath} for course ID: {$courseId}");
            }

            $newVideo = $this->videoService->create([
                'course_id' => $courseId,
                'title' => $videoData['title'],
                'description' => $videoData['description'],
                'video_url' => $videoPath,
                'order' => $videoData['order'],
            ]);
            $createdVideos[] = $newVideo;
            Log::info('Video created:', $newVideo->toArray());
        }

        return response()->json($createdVideos, 201);
    }

    public function show(int $id): JsonResponse
    {
        $video = $this->videoService->getById($id);
        return response()->json($video);
    }

    public function update(Request $request, int $id): JsonResponse
    {
        $video = $this->videoService->update($id, $request->all());
        return response()->json($video);
    }

    public function destroy(int $id): JsonResponse
    {
        $this->videoService->delete($id);
        return response()->json(['message' => 'Video deleted successfully']);
    }
}
