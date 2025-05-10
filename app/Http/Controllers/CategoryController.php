<?php

namespace App\Http\Controllers;

use App\Http\Requests\CategoryRequest;
use App\Services\CategoryService;
use Illuminate\Http\JsonResponse;

class CategoryController extends Controller
{
    public function __construct(protected CategoryService $categoryService)
    {}

    public function index(): JsonResponse
    {
        $categories = $this->categoryService->getAll();
        return response()->json($categories);
    }

    public function store(CategoryRequest $request): JsonResponse
    {
        $category = $this->categoryService->create($request->validated());
        return response()->json($category, 201);
    }

    public function show(int $id): JsonResponse
    {
        $category = $this->categoryService->getById($id);
        return response()->json($category);
    }

    public function update(CategoryRequest $request, int $id): JsonResponse
    {
        $category = $this->categoryService->update($id, $request->validated());
        return response()->json($category);
    }

    public function destroy(int $id): JsonResponse
    {
        $this->categoryService->delete($id);
        return response()->json(['message' => 'Category deleted successfully']);
    }
}
