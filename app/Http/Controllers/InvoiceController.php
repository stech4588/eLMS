<?php
namespace App\Http\Controllers;

use App\Http\Requests\InvoiceRequest;
use App\Services\InvoiceService;
use Illuminate\Http\JsonResponse;

class InvoiceController extends Controller
{
    public function __construct(protected InvoiceService $invoiceService) {}

    public function index() : JsonResponse
    {
        return response()->json($this->invoiceService->getAll());
    }

    public function store(InvoiceRequest $request) : JsonResponse
    {
        return response()->json($this->invoiceService->create($request->validated()), 201);
    }

    public function show($id) : JsonResponse
    {
        return response()->json($this->invoiceService->getById($id));
    }

    public function update(InvoiceRequest $request, $id) : JsonResponse
    {
        return response()->json($this->invoiceService->update($id, $request->validated()));
    }

    public function destroy($id) : JsonResponse
    {
        $this->invoiceService->delete($id);
        return response()->json(['message' => 'Invoice deleted successfully']);
    }
}

