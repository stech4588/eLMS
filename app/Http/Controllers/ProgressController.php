<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProgressRequest;
use App\Services\ProgressService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Models\Course;
use App\Models\Progress;

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
            $result = $this->progressService->updateOrCreateProgress($request->validated());

            if ($request->wantsJson() && !$request->header('X-Inertia')) {
                return response()->json($result, 201);
            }

            return back()
                ->with('course_completed', $result['course_completed'])
                ->with('already_completed', $result['already_completed'] ?? false);

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

    public function getCourseProgress(Request $request, Course $course): JsonResponse
    {
        $user = $request->user();
        if (!$user) {
            return response()->json(['message' => 'User not authenticated.'], 401);
        }
        $progress = $this->progressService->getProgressForUserAndCourse($user, $course);
        return response()->json($progress);
    }

    public function getCompletionStatus(Request $request, Course $course): JsonResponse
    {
        $user = $request->user();
        if (!$user) {
            return response()->json(['is_completed' => false, 'message' => 'User not authenticated.'], 401);
        }

        $isCompleted = $this->progressService->isCourseCompletedAndUnreviewed($user, $course);
        return response()->json(['is_completed' => $isCompleted]);
    }

    public function getCompletedCount(Request $request, Course $course): JsonResponse
    {
        $user = $request->user();
        if (!$user) {
            return response()->json(['completed_count' => 0]);
        }

        $course->loadMissing('videos');
        $videoIds = $course->videos->pluck('id');
        $count = Progress::where('user_id', $user->id)
            ->whereIn('video_id', $videoIds)
            ->where('completed', true)
            ->count();

        return response()->json([
            'completed_count' => $count,
            'total_count' => $videoIds->count(),
        ]);
    }
}
