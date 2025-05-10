<?php

namespace App\Http\Controllers;

use App\Http\Requests\VideoRequest;
use App\Services\VideoService;
use Illuminate\Http\JsonResponse;

class VideoController extends Controller
{
    public function __construct(protected VideoService $videoService)
    {}

    public function index(): JsonResponse
    {
        $videos = $this->videoService->getAll();
        return response()->json($videos);
    }

    public function store(VideoRequest $request): JsonResponse
    {
        $video = $this->videoService->create($request->validated());
        return response()->json($video, 201);
    }

    public function show(int $id): JsonResponse
    {
        $video = $this->videoService->getById($id);
        return response()->json($video);
    }

    public function update(VideoRequest $request, int $id): JsonResponse
    {
        $video = $this->videoService->update($id, $request->validated());
        return response()->json($video);
    }

    public function destroy(int $id): JsonResponse
    {
        $this->videoService->delete($id);
        return response()->json(['message' => 'Video deleted successfully']);
    }
}
