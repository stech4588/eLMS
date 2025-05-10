<?php

namespace App\Http\Controllers;

use App\Http\Requests\ReviewRequest;
use App\Services\ReviewService;
use Illuminate\Http\JsonResponse;

class ReviewController extends Controller
{
    public function __construct(protected ReviewService $reviewService) {}

    public function index() : JsonResponse
    {
        return response()->json($this->reviewService->getAll());
    }

    public function store(ReviewRequest $request) : JsonResponse
    {
        return response()->json($this->reviewService->create($request->validated()), 201);
    }

    public function show($id) : JsonResponse
    {
        return response()->json($this->reviewService->getById($id));
    }

    public function update(ReviewRequest $request, $id) : JsonResponse
    {
        return response()->json($this->reviewService->update($id, $request->validated()));
    }

    public function destroy($id) : JsonResponse
    {
        $this->reviewService->delete($id);
        return response()->json(['message' => 'Review deleted successfully']);
    }
}
