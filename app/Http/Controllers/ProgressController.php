<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProgressRequest;
use App\Services\ProgressService;
use Illuminate\Http\JsonResponse;

class ProgressController extends Controller
{
    public function __construct(protected ProgressService $progressService) {}

    public function index() : JsonResponse
    {
        return response()->json($this->progressService->getAll());
    }

    public function store(ProgressRequest $request) : JsonResponse
    {
        return response()->json($this->progressService->create($request->validated()), 201);
    }

    public function show($id) : JsonResponse
    {
        return response()->json($this->progressService->getById($id));
    }

    public function update(ProgressRequest $request, $id) : JsonResponse
    {
        return response()->json($this->progressService->update($id, $request->validated()));
    }

    public function destroy($id) : JsonResponse
    {
        $this->progressService->delete($id);
        return response()->json(['message' => 'Progress deleted successfully']);
    }
}
