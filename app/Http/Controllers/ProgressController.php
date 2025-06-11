<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProgressRequest;
use App\Services\ProgressService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ProgressController extends Controller
{
    public function __construct(protected ProgressService $progressService) {}

    public function index() : JsonResponse
    {
        return response()->json($this->progressService->getAll());
    }

    public function store(ProgressRequest $request) : JsonResponse
    {
        return response()->json($this->progressService->updateOrCreateProgress($request->validated()), 201);
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

    /**
     * Store a newly created progress record using a dedicated route.
     *
     * @param ProgressRequest $request
     * @return JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function storeUserVideoProgress(ProgressRequest $request)
    {
        try {
            $progress = $this->progressService->updateOrCreateProgress($request->validated());

            if ($request->wantsJson() && !$request->header('X-Inertia')) {
                // For non-Inertia (axios) requests that want JSON
                return response()->json($progress, 201);
            }

            // For Inertia requests, redirect back. Inertia will handle this.
            // You might want to add a flash message if needed, e.g., ->with('success', 'Progress saved!');
            return redirect()->back()->with('inertia_handled_post', true); // 302 redirect, or 303 with ->withInput()

        } catch (\Exception $e) {
            Log::error('Progress save error in storeUserVideoProgress: ' . $e->getMessage() . ' Trace: ' . $e->getTraceAsString());
            
            // For Inertia requests, errors should ideally be handled by redirecting back with errors
            // or by returning a specific error response that Inertia can process.
            if ($request->wantsJson() && !$request->header('X-Inertia')) {
                 return response()->json([
                    'message' => 'Failed to save progress',
                    'error' => $e->getMessage()
                ], 500);
            }

            // For Inertia form errors, redirecting back with error messages is typical
            // but for simplicity, if it's an Inertia request and an error occurs, we can return a 500
            // Inertia's form `onError` will receive the error bag.
            // Or, handle it by flashing errors to session and redirecting back.
            return response()->json([ // This will be caught by Inertia form's onError
                'message' => 'Failed to save progress: ' . $e->getMessage(),
            ], 500); 
        }
    }

    /**
     * Get the progress for the current user and a specific video.
     *
     * @param Request $request
     * @param int $videoId
     * @return JsonResponse
     */
    public function getUserVideoProgress(Request $request, int $videoId): JsonResponse
    {
        $user = $request->user();
        if (!$user) {
            return response()->json(['message' => 'User not authenticated.'], 401);
        }

        $progress = $this->progressService->getProgressForUserAndVideo($user->id, $videoId);

        if ($progress) {
            return response()->json($progress);
        } else {
            return response()->json(null, 200); // Return null or an empty object if no progress, with 200 OK
        }
    }
}
