<?php

namespace App\Http\Controllers;

use App\Http\Requests\CourseRequest;
use App\Services\CourseService;
use Illuminate\Http\JsonResponse;

class CourseController extends Controller
{
    protected CourseService $courseService;

    public function __construct(CourseService $courseService)
    {
        $this->courseService = $courseService;
    }

    public function index(): JsonResponse
    {
        $courses = $this->courseService->getAll();
        return response()->json($courses);
    }

    public function store(CourseRequest $request): JsonResponse
    {
        $course = $this->courseService->create($request->validated());
        return response()->json($course, 201);
    }

    public function show(int $id): JsonResponse
    {
        $course = $this->courseService->getById($id);
        return response()->json($course);
    }

    public function update(CourseRequest $request, int $id): JsonResponse
    {
        $course = $this->courseService->update($id, $request->validated());
        return response()->json($course);
    }

    public function destroy(int $id): JsonResponse
    {
        $this->courseService->delete($id);
        return response()->json(['message' => 'Course deleted successfully']);
    }
}
