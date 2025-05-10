<?php

namespace App\Http\Controllers;

use App\Http\Requests\WatchlistRequest;
use App\Services\WatchlistService;
use Illuminate\Http\JsonResponse;

class WatchlistController extends Controller
{
    public function __construct(protected WatchlistService $watchlistService) {}

    public function index() : JsonResponse
    {
        return response()->json($this->watchlistService->getAll());
    }

    public function store(WatchlistRequest $request) : JsonResponse
    {
        return response()->json($this->watchlistService->create($request->validated()), 201);
    }

    public function show($id) : JsonResponse
    {
        return response()->json($this->watchlistService->getById($id));
    }

    public function update(WatchlistRequest $request, $id) : JsonResponse
    {
        return response()->json($this->watchlistService->update($id, $request->validated()));
    }

    public function destroy($id) : JsonResponse
    {
        $this->watchlistService->delete($id);
        return response()->json(['message' => 'Watchlist deleted successfully']);
    }
}
