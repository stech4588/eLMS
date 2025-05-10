<?php
namespace App\Http\Controllers;

use App\Http\Requests\EnrollmentRequest;
use App\Services\EnrollmentService;
use Illuminate\Http\JsonResponse;

class EnrollmentController extends Controller
{
    public function __construct(protected EnrollmentService $enrollmentService) {}

    public function index() : JsonResponse
    {
        return response()->json($this->enrollmentService->getAll());
    }

    public function store(EnrollmentRequest $request) : JsonResponse
    {
        return response()->json($this->enrollmentService->create($request->validated()), 201);
    }

    public function show($id) : JsonResponse
    {
        return response()->json($this->enrollmentService->getById($id));
    }

    public function update(EnrollmentRequest $request, $id) : JsonResponse
    {
        return response()->json($this->enrollmentService->update($id, $request->validated()));
    }

    public function destroy($id) : JsonResponse
    {
        $this->enrollmentService->delete($id);
        return response()->json(['message' => 'Enrollment deleted successfully']);
    }
}

