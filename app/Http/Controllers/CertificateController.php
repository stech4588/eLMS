<?php

namespace App\Http\Controllers;

use App\Http\Requests\CertificateRequest;
use App\Services\CertificateService;
use Illuminate\Http\JsonResponse;

class CertificateController extends Controller
{
    public function __construct(protected CertificateService $certificateService) {}

    public function index() : JsonResponse
    {
        return response()->json($this->certificateService->getAll());
    }

    public function store(CertificateRequest $request) : JsonResponse
    {
        return response()->json($this->certificateService->create($request->validated()), 201);
    }

    public function show($id) : JsonResponse
    {
        return response()->json($this->certificateService->getById($id));
    }

    public function update(CertificateRequest $request, $id) : JsonResponse
    {
        return response()->json($this->certificateService->update($id, $request->validated()));
    }

    public function destroy($id) : JsonResponse
    {
        $this->certificateService->delete($id);
        return response()->json(['message' => 'Review deleted successfully']);
    }
}
