<?php

namespace App\Http\Controllers;

use App\Http\Requests\CourseRequest;
use App\Services\CourseService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use App\Models\CourseCertificate; // Assuming you have a Certificate model
use App\Models\CourseIndustry;    // Assuming you have an Industry model
use App\Models\CourseType;
use App\Models\CourseTopic; // Added CourseTopic model
use Inertia\Response as InertiaResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Str; // Import Str facade
use Illuminate\Support\Facades\File; // Import File facade for directory creation
use Illuminate\Support\Facades\Storage; // Import Storage facade for efficient file handling
use App\Models\Course;
use App\Models\CourseSection;
use App\Models\Invoice;
use Illuminate\Support\Facades\Auth;
use App\Events\CourseViewed; // Import the CourseViewed event
use App\Models\Review;
use App\Models\Progress;
use Illuminate\Support\Facades\Mail; // Import Mail Facade
use App\Mail\NewCourseNotification; // Import the Mailable class
use App\Models\User; // Import the User model
use App\Models\Quiz;
use Illuminate\Support\Facades\Http;
use App\Models\Video;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

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

    public function store(CourseRequest $request): RedirectResponse
    {
        try {
            $course = $this->courseService->create($request->validated());
            return redirect('/dashboard')
                ->with('success', 'Course created successfully!')
                ->with('course_id', $course->id);
        } catch (\Throwable $e) {
            Log::error('Course creation failed', [
                'user_id' => optional($request->user())->id,
                'title' => $request->input('title'),
                'message' => $e->getMessage(),
            ]);
            return redirect()->back()
                ->withInput()
                ->with('error', 'Failed to create course. Please try again.');
        }
    }

    public function create(): InertiaResponse
    {
        return Inertia::render('addCourses/addNewCourses', array_merge(
            $this->getCourseFormOptions(),
            [
                'course' => null,
                'courseSections' => [],
                'courseVideos' => [],
                'courseQuiz' => null,
                'isEditing' => false,
            ]
        ));
    }

    public function edit(Request $request, Course $course): InertiaResponse
    {
        $this->authorizeCourseOwner($request, $course);

        $course->load([
            'courseType',
            'industry',
            'certificate',
            'topic',
            'sections' => function ($q) {
                $q->orderBy('order');
            },
            'sections.videos' => function ($q) {
                $q->orderBy('order');
            },
            'videos.quiz.questions.answers',
            'videos.courseSection',
            'quizzes.questions.answers',
        ]);

        $courseData = [
            'id' => $course->id,
            'title' => $course->title,
            'description' => $course->description,
            'additional_description' => $course->additional_description,
            'recomendations' => $course->recomendations,
            'certificate_id' => $course->certificate_id,
            'industry_id' => $course->industry_id,
            'course_type_id' => $course->course_type_id,
            'topic_id' => $course->topic_id,
            'price' => $course->price,
        ];

        $courseVideos = $course->videos->sortBy('order')->values()->map(function ($video) {
            return [
                'id' => $video->id,
                'course_section_id' => $video->course_section_id,
                'title' => $video->title,
                'description' => $video->description,
                'takeaway_notes' => $video->takeaway_notes,
                'order' => $video->order,
                'duration_in_seconds' => $video->duration,
                'video_url' => $video->video_url ? asset($video->video_url) : null,
                'video_path' => $video->video_url,
                'thumbnail_url' => $video->thumbnail_url ? asset($video->thumbnail_url) : null,
                'thumbnail_path' => $video->thumbnail_url,
                'quiz' => $video->quiz ? [
                    'title' => $video->quiz->title,
                    'description' => $video->quiz->description,
                    'questions' => $video->quiz->questions->map(function ($question) {
                        return [
                            'question_text' => $question->question_text,
                            'answers' => $question->answers->map(function ($answer) {
                                return [
                                    'answer_text' => $answer->answer_text,
                                    'is_correct' => (bool) $answer->is_correct,
                                ];
                            })->values(),
                        ];
                    })->values(),
                ] : null,
            ];
        })->values();

        $courseSections = $course->sections->map(function ($s) {
            return [
                'id' => $s->id,
                'title' => $s->title,
                'order' => $s->order,
            ];
        })->values();

        // Backward compatibility: if course has videos but no sections, create one default section and assign videos to it
        if ($courseSections->isEmpty() && $course->videos->isNotEmpty()) {
            $defaultSection = CourseSection::create([
                'course_id' => $course->id,
                'title' => 'Section 1',
                'order' => 0,
            ]);
            $course->videos()->update(['course_section_id' => $defaultSection->id]);
            $course->load('sections');
            $courseSections = $course->sections->map(function ($s) {
                return [
                    'id' => $s->id,
                    'title' => $s->title,
                    'order' => $s->order,
                ];
            })->values();
        }

        $courseQuizModel = $course->quizzes->first();
        $courseQuiz = $courseQuizModel ? [
            'id' => $courseQuizModel->id,
            'title' => $courseQuizModel->title,
            'description' => $courseQuizModel->description,
            'questions' => $courseQuizModel->questions->map(function ($question) {
                return [
                    'id' => $question->id,
                    'question_text' => $question->question_text,
                    'answers' => $question->answers->map(function ($answer) {
                        return [
                            'id' => $answer->id,
                            'answer_text' => $answer->answer_text,
                            'is_correct' => (bool) $answer->is_correct,
                        ];
                    })->values(),
                ];
            })->values(),
        ] : null;

        return Inertia::render('addCourses/addNewCourses', array_merge(
            $this->getCourseFormOptions(),
            [
                'course' => $courseData,
                'courseSections' => $courseSections,
                'courseVideos' => $courseVideos,
                'courseQuiz' => $courseQuiz,
                'isEditing' => true,
            ]
        ));
    }

    public function saveDraft(Request $request): JsonResponse
    {
        // Increase time limits for large file uploads (25-30 minutes)
        set_time_limit(1800); // 30 minutes
        ini_set('max_execution_time', 1800);
        ini_set('max_input_time', 1800);
        
        // Send headers to prevent nginx timeout (if possible)
        if (!headers_sent()) {
            header('X-Accel-Buffering: no'); // Disable nginx buffering
        }
        
        $validatedCourseData = $request->validate([
            'title' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'price' => 'nullable|numeric|min:0',
            'additional_description' => 'nullable|string',
            'recomendations' => 'nullable|string',
            'certificates' => 'nullable|exists:course_certificates,id',
            'industry' => 'nullable|exists:course_industries,id',
            'course_type' => 'nullable|exists:course_types,id',
            'topic' => 'nullable|exists:topics,id',
            'course_id' => 'nullable|exists:courses,id',
            'videos' => 'nullable|array',
            'videos.*.id' => 'nullable|exists:videos,id',
            'videos.*.title' => 'nullable|string|max:255',
            'videos.*.description' => 'nullable|string',
            'videos.*.takeaway_notes' => 'nullable|string',
            'videos.*.order' => 'nullable|integer',
            'videos.*.quiz' => 'nullable|string', // JSON string
            'videos.*.videoFile' => 'nullable|file|mimes:mp4,mov,ogg,qt|max:512000',
            'videos.*.thumbnailFile' => 'nullable|image|mimes:jpg,jpeg,png,gif|max:2048',
            'videos.*.duration_in_seconds' => 'nullable|integer|min:0',
            'videos.*.course_section_id' => 'nullable|exists:course_sections,id',
            'videos.*.section_index' => 'nullable|integer|min:0',
            'removed_video_ids' => 'nullable|array',
            'removed_video_ids.*' => 'integer|exists:videos,id',
            'sections' => 'nullable|array',
            'sections.*.id' => 'nullable|exists:course_sections,id',
            'sections.*.title' => 'nullable|string|max:255',
            'sections.*.order' => 'nullable|integer|min:0',
            'removed_section_ids' => 'nullable|array',
            'removed_section_ids.*' => 'integer|exists:course_sections,id',
        ]);

        DB::beginTransaction();

        try {
            $courseId = $validatedCourseData['course_id'] ?? null;
            $course = null;
            
            if ($courseId) {
                // Update existing draft
                $course = Course::findOrFail($courseId);
                $this->authorizeCourseOwner($request, $course);

                $courseDataToUpdate = [
                    'title' => $validatedCourseData['title'] ?? $course->title,
                    'description' => $validatedCourseData['description'] ?? $course->description,
                    'price' => $validatedCourseData['price'] ?? $course->price,
                    'additional_description' => $validatedCourseData['additional_description'] ?? $course->additional_description,
                    'recomendations' => $validatedCourseData['recomendations'] ?? $course->recomendations,
                    'certificate_id' => $validatedCourseData['certificates'] ?? $course->certificate_id,
                    'industry_id' => $validatedCourseData['industry'] ?? $course->industry_id,
                    'course_type_id' => $validatedCourseData['course_type'] ?? $course->course_type_id,
                    'topic_id' => $validatedCourseData['topic'] ?? $course->topic_id,
                    'status' => 'draft',
                ];

                $course->update($courseDataToUpdate);
            } else {
                // Reuse recent empty draft to avoid duplicate courses (e.g. multiple rapid save-draft calls)
                $recentDraft = Course::where('user_id', $request->user()->id)
                    ->where('status', 'draft')
                    ->where(function ($q) {
                        $q->where('title', 'Untitled Course')
                            ->orWhereNull('title')
                            ->orWhere('title', '');
                    })
                    ->where('created_at', '>=', now()->subMinutes(3))
                    ->orderByDesc('id')
                    ->first();

                if ($recentDraft) {
                    $course = $recentDraft;
                    $courseDataToUpdate = [
                        'title' => $validatedCourseData['title'] ?? $course->title,
                        'description' => $validatedCourseData['description'] ?? $course->description,
                        'price' => $validatedCourseData['price'] ?? $course->price,
                        'additional_description' => $validatedCourseData['additional_description'] ?? $course->additional_description,
                        'recomendations' => $validatedCourseData['recomendations'] ?? $course->recomendations,
                        'certificate_id' => $validatedCourseData['certificates'] ?? $course->certificate_id,
                        'industry_id' => $validatedCourseData['industry'] ?? $course->industry_id,
                        'course_type_id' => $validatedCourseData['course_type'] ?? $course->course_type_id,
                        'topic_id' => $validatedCourseData['topic'] ?? $course->topic_id,
                        'status' => 'draft',
                    ];
                    $course->update($courseDataToUpdate);
                } else {
                    $courseDataToCreate = [
                        'user_id' => $request->user()->id,
                        'title' => $validatedCourseData['title'] ?? 'Untitled Course',
                        'description' => $validatedCourseData['description'] ?? '',
                        'price' => $validatedCourseData['price'] ?? 0,
                        'additional_description' => $validatedCourseData['additional_description'] ?? null,
                        'recomendations' => $validatedCourseData['recomendations'] ?? null,
                        'certificate_id' => $validatedCourseData['certificates'] ?? null,
                        'industry_id' => $validatedCourseData['industry'] ?? null,
                        'course_type_id' => $validatedCourseData['course_type'] ?? null,
                        'topic_id' => $validatedCourseData['topic'] ?? null,
                        'status' => 'draft',
                    ];
                    $course = $this->courseService->create($courseDataToCreate);
                }
            }

            // Sync sections (create/update/delete)
            if ($course->exists) {
                if ($request->has('removed_section_ids') && is_array($request->input('removed_section_ids'))) {
                    CourseSection::where('course_id', $course->id)
                        ->whereIn('id', $request->input('removed_section_ids'))
                        ->delete();
                }
                if ($request->has('sections') && is_array($request->input('sections'))) {
                    $order = 0;
                    foreach ($request->input('sections') as $sectionData) {
                        $title = isset($sectionData['title']) ? trim($sectionData['title']) : '';
                        if ($title === '') {
                            continue;
                        }
                        if (!empty($sectionData['id'])) {
                            $section = CourseSection::where('course_id', $course->id)->where('id', $sectionData['id'])->first();
                            if ($section) {
                                $section->update([
                                    'title' => $title,
                                    'order' => (int) ($sectionData['order'] ?? $order),
                                ]);
                                $order++;
                            }
                        } else {
                            // firstOrCreate to avoid duplicate sections (same course + title)
                            $section = CourseSection::firstOrCreate(
                                [
                                    'course_id' => $course->id,
                                    'title' => $title,
                                ],
                                [
                                    'order' => (int) ($sectionData['order'] ?? $order),
                                ]
                            );
                            if ($section->wasRecentlyCreated === false) {
                                $section->update(['order' => (int) ($sectionData['order'] ?? $order)]);
                            }
                            $order++;
                        }
                    }
                }
            }

            // Save videos if provided
            if ($request->has('videos') && is_array($request->input('videos'))) {
                $videoService = resolve(\App\Services\VideoService::class);
                $createdVideoIds = []; // Track created/updated video IDs
                $sectionIdsOrdered = $course->sections()->orderBy('order')->pluck('id')->values();

                foreach ($request->input('videos') as $index => $videoData) {
                    $videoId = $videoData['id'] ?? null;
                    $courseSectionId = $videoData['course_section_id'] ?? null;
                    if (empty($courseSectionId) && isset($videoData['section_index'])) {
                        $courseSectionId = $sectionIdsOrdered->get((int) $videoData['section_index']);
                    }
                    
                    // Handle video file upload
                    $videoFile = $request->file("videos.{$index}.videoFile");
                    $thumbnailFile = $request->file("videos.{$index}.thumbnailFile");
                    $videoPath = null;
                    $thumbnailPath = null;
                    
                    if ($videoFile) {
                        $videoTargetDirectory = 'uploads/course_' . $course->id . '_videos';
                        $videoFileName = time() . '_' . Str::slug(pathinfo($videoFile->getClientOriginalName(), PATHINFO_FILENAME)) . '.' . $videoFile->getClientOriginalExtension();
                        
                        try {
                            // Use Storage for more efficient file handling with streams
                            // First ensure directory exists
                            $fullPath = public_path($videoTargetDirectory);
                            
                            if (!File::isDirectory($fullPath)) {
                                File::makeDirectory($fullPath, 0755, true, true);
                            }
                            
                            // Use stream-based upload for large files to prevent memory issues
                            $sourcePath = $videoFile->getRealPath();
                            $destinationPath = $fullPath . '/' . $videoFileName;
                            
                            if ($sourcePath && file_exists($sourcePath)) {
                                $sourceStream = fopen($sourcePath, 'rb');
                                $destinationStream = fopen($destinationPath, 'wb');
                                
                                if ($sourceStream && $destinationStream) {
                                    // Stream copy in chunks to handle large files efficiently
                                    $chunkSize = 1024 * 1024; // 1MB chunks for better performance
                                    $bytesCopied = 0;
                                    $totalSize = filesize($sourcePath);
                                    $lastFlushSize = 0;
                                    $flushInterval = 10 * 1024 * 1024; // Flush every 10MB
                                    
                                    while (!feof($sourceStream)) {
                                        $chunk = fread($sourceStream, $chunkSize);
                                        if ($chunk !== false && strlen($chunk) > 0) {
                                            fwrite($destinationStream, $chunk);
                                            $bytesCopied += strlen($chunk);
                                            
                                            // Flush output periodically to prevent timeout
                                            if (($bytesCopied - $lastFlushSize) >= $flushInterval) {
                                                if (ob_get_level() > 0) {
                                                    ob_flush();
                                                }
                                                flush();
                                                $lastFlushSize = $bytesCopied;
                                            }
                                        }
                                    }
                                    fclose($sourceStream);
                                    fclose($destinationStream);
                                    
                                    // Verify file was copied successfully
                                    if (file_exists($destinationPath) && filesize($destinationPath) > 0) {
                                        $videoPath = $videoTargetDirectory . '/' . $videoFileName;
                                    } else {
                                        throw new \Exception('File copy verification failed');
                                    }
                                } else {
                                    // Fallback to move if stream fails
                                    $videoFile->move($fullPath, $videoFileName);
                                    $videoPath = $videoTargetDirectory . '/' . $videoFileName;
                                }
                            } else {
                                // Fallback to move if getRealPath fails
                                $videoFile->move($fullPath, $videoFileName);
                                $videoPath = $videoTargetDirectory . '/' . $videoFileName;
                            }
                        } catch (\Throwable $e) {
                            Log::error('Video file upload failed in draft save', [
                                'error' => $e->getMessage(),
                            ]);
                            // Don't fail the entire request, just log and continue
                        }
                    }
                    
                    if ($thumbnailFile) {
                        $thumbTargetDirectory = 'uploads/course_' . $course->id . '_video_thumbnails';
                        $thumbFileName = time() . '_' . Str::slug(pathinfo($thumbnailFile->getClientOriginalName(), PATHINFO_FILENAME)) . '.' . $thumbnailFile->getClientOriginalExtension();
                        
                        try {
                            // Thumbnails are smaller, so regular move is fine
                            $fullThumbPath = public_path($thumbTargetDirectory);
                            if (!File::isDirectory($fullThumbPath)) {
                                File::makeDirectory($fullThumbPath, 0755, true, true);
                            }
                            $thumbnailFile->move($fullThumbPath, $thumbFileName);
                            $thumbnailPath = $thumbTargetDirectory . '/' . $thumbFileName;
                        } catch (\Throwable $e) {
                            Log::error('Thumbnail file upload failed in draft save', [
                                'error' => $e->getMessage(),
                            ]);
                        }
                    }
                    
                    // Handle quiz data - it might be JSON string
                    $quizData = null;
                    if (isset($videoData['quiz'])) {
                        if (is_string($videoData['quiz'])) {
                            // Try to decode JSON string
                            $decoded = json_decode($videoData['quiz'], true);
                            $quizData = $decoded ?: null;
                        } elseif (is_array($videoData['quiz'])) {
                            $quizData = $videoData['quiz'];
                        }
                    }
                    
                    if ($videoId) {
                        // Update existing video
                        $existingVideo = $course->videos()->where('id', $videoId)->first();
                        
                        if ($existingVideo) {
                            $updatePayload = [
                                'title' => $videoData['title'] ?? $existingVideo->title,
                                'description' => $videoData['description'] ?? $existingVideo->description,
                                'takeaway_notes' => $videoData['takeaway_notes'] ?? $existingVideo->takeaway_notes,
                                'order' => $videoData['order'] ?? $existingVideo->order,
                                'course_section_id' => $courseSectionId ?? $existingVideo->course_section_id,
                            ];
                            
                            // Update video URL if new file uploaded
                            if ($videoPath) {
                                $updatePayload['video_url'] = $videoPath;
                            }
                            
                            // Update thumbnail URL if new file uploaded
                            if ($thumbnailPath) {
                                $updatePayload['thumbnail_url'] = $thumbnailPath;
                            }
                            
                            // Update duration if provided
                            if (isset($videoData['duration_in_seconds'])) {
                                $updatePayload['duration'] = $videoData['duration_in_seconds'];
                            }
                            
                            $videoService->update($existingVideo->id, $updatePayload);
                            
                            // Sync quiz if provided - allow empty questions array for draft
                            if ($quizData && is_array($quizData) && !empty($quizData['title'])) {
                                // Ensure questions array exists even if empty
                                if (!isset($quizData['questions'])) {
                                    $quizData['questions'] = [];
                                }
                                $this->syncVideoQuiz($existingVideo, $quizData);
                            }
                            
                            // Store video ID for response
                            $createdVideoIds[$index] = $existingVideo->id;
                        }
                    } else {
                        // Check if video with same order already exists (to avoid duplicates on auto-save)
                        // Priority: Check by order first, then by empty video_url (draft videos)
                        $existingVideoByOrder = $course->videos()
                            ->where('order', $videoData['order'] ?? 1)
                            ->where(function($query) use ($videoData) {
                                // Match by title if provided, or find draft videos (empty video_url)
                                if (!empty($videoData['title'])) {
                                    $query->where('title', $videoData['title']);
                                }
                                $query->orWhere('video_url', '')
                                      ->orWhereNull('video_url');
                            })
                            ->orderBy('created_at', 'desc') // Get most recent one
                            ->first();
                        
                        if ($existingVideoByOrder) {
                            // Update existing video instead of creating new one
                            $updatePayload = [
                                'title' => $videoData['title'] ?? $existingVideoByOrder->title,
                                'description' => $videoData['description'] ?? $existingVideoByOrder->description,
                                'takeaway_notes' => $videoData['takeaway_notes'] ?? $existingVideoByOrder->takeaway_notes,
                                'order' => $videoData['order'] ?? $existingVideoByOrder->order,
                                'course_section_id' => $courseSectionId ?? $existingVideoByOrder->course_section_id,
                            ];
                            
                            if ($videoPath) {
                                $updatePayload['video_url'] = $videoPath;
                            }
                            
                            if ($thumbnailPath) {
                                $updatePayload['thumbnail_url'] = $thumbnailPath;
                            }
                            
                            if (isset($videoData['duration_in_seconds'])) {
                                $updatePayload['duration'] = $videoData['duration_in_seconds'];
                            }
                            
                            $videoService->update($existingVideoByOrder->id, $updatePayload);
                            
                            if ($quizData && is_array($quizData)) {
                                $this->syncVideoQuiz($existingVideoByOrder, $quizData);
                            }
                            
                            // Store video ID for response
                            $createdVideoIds[$index] = $existingVideoByOrder->id;
                        } else {
                            // Create new video with file uploads if available
                            $newVideo = $videoService->create([
                                'course_id' => $course->id,
                                'course_section_id' => $courseSectionId,
                                'title' => $videoData['title'] ?? 'Untitled Video',
                                'description' => $videoData['description'] ?? '',
                                'takeaway_notes' => $videoData['takeaway_notes'] ?? null,
                                'video_url' => $videoPath ?: '', // Use uploaded path or empty string
                                'thumbnail_url' => $thumbnailPath,
                                'order' => $videoData['order'] ?? 1,
                                'duration' => $videoData['duration_in_seconds'] ?? null,
                            ]);
                            
                            // Sync quiz if provided
                            if ($quizData && is_array($quizData)) {
                                $this->syncVideoQuiz($newVideo, $quizData);
                            }
                            
                            // Store video ID for response
                            $createdVideoIds[$index] = $newVideo->id;
                        }
                    }
                }
            }
            
            // Handle removed video IDs - delete videos that were removed
            if ($request->has('removed_video_ids') && is_array($request->input('removed_video_ids'))) {
                $removedVideoIds = $request->input('removed_video_ids');
                $videosToDelete = $course->videos()->whereIn('id', $removedVideoIds)->get();
                
                foreach ($videosToDelete as $videoToDelete) {
                    // Remove associated quiz first
                    if ($videoToDelete->quiz) {
                        $videoToDelete->quiz->questions()->each(function ($question) {
                            $question->answers()->delete();
                        });
                        $videoToDelete->quiz->questions()->delete();
                        $videoToDelete->quiz()->delete();
                    }
                    // Delete the video
                    $videoToDelete->delete();
                }
            }

            DB::commit();

            $sectionIds = $course->sections()->orderBy('order')->pluck('id')->values()->all();

            return response()->json([
                'success' => true,
                'message' => 'Draft saved successfully',
                'course_id' => $course->id,
                'draftCourseId' => $course->id,
                'video_ids' => $createdVideoIds ?? [],
                'section_ids' => $sectionIds,
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            
            $errorMessage = $e->getMessage();
            $statusCode = 500;
            
            // Check if it's a timeout-related error
            if (str_contains($errorMessage, 'timeout') || 
                str_contains($errorMessage, 'Maximum execution time') ||
                str_contains($errorMessage, '504')) {
                $errorMessage = 'Upload timeout: The file is too large or the server is taking too long to process it. Please try uploading a smaller file or contact support.';
                $statusCode = 504;
            }
            
            Log::error('Draft save failed', [
                'user_id' => $request->user()->id ?? null,
                'course_id' => $courseId ?? null,
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'request_size' => $request->header('Content-Length'),
            ]);
            
            return response()->json([
                'success' => false,
                'message' => $errorMessage,
                'error_type' => 'draft_save_failed',
            ], $statusCode);
        }
    }

    public function storeWithVideos(Request $request): RedirectResponse
    {

        $validatedCourseData = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'nullable|numeric|min:0',
            'additional_description' => 'required|string',
            'recomendations' => 'required|string',
            'certificates' => 'required|exists:course_certificates,id',
            'industry' => 'required|exists:course_industries,id',
            'course_type' => 'required|exists:course_types,id',
            'topic' => 'required|exists:topics,id',
        ]);

        $validatedVideosData = $request->validate([
            'videos' => 'present|array',
            'videos.*.title' => 'required_with:videos|string|max:255',
            'videos.*.description' => 'required_with:videos|string',
            'videos.*.takeaway_notes' => 'nullable|string',
            'videos.*.videoFile' => 'required_with:videos|file|mimes:mp4,mov,ogg,qt|max:512000',
            'videos.*.thumbnailFile' => 'nullable|image|mimes:jpg,jpeg,png,gif|max:2048',
            'videos.*.order' => 'required_with:videos|integer',
            // Per-video quiz validation - if quiz data exists, it must be complete
            'videos.*.quiz' => 'nullable|array',
            'videos.*.quiz.title' => 'required_with:videos.*.quiz|string|max:255',
            'videos.*.quiz.description' => 'nullable|string',
            'videos.*.quiz.questions' => 'required_with:videos.*.quiz|array|min:1',
            'videos.*.quiz.questions.*.question_text' => 'required_with:videos.*.quiz.questions|string',
            'videos.*.quiz.questions.*.answers' => 'required_with:videos.*.quiz.questions|array|min:2',
            'videos.*.quiz.questions.*.answers.*.answer_text' => 'required_with:videos.*.quiz.questions.*.answers|string',
            'videos.*.quiz.questions.*.answers.*.is_correct' => 'boolean',
        ]);

        $validatedQuizData = $request->validate([
            'quiz' => 'sometimes|array',
            'quiz.title' => 'required_with:quiz|string|max:255',
            'quiz.description' => 'nullable|string',
            'quiz.questions' => 'required_with:quiz|array|min:1',
            'quiz.questions.*.question_text' => 'required_with:quiz.questions|string',
            'quiz.questions.*.answers' => 'required_with:quiz.questions|array|min:2',
            'quiz.questions.*.answers.*.answer_text' => 'required_with:quiz.questions.*.answers|string',
            'quiz.questions.*.answers.*.is_correct' => 'boolean',
        ]);

        DB::beginTransaction();

        try {
            $courseDataToCreate = [
                'user_id' => $request->user()->id,
                'title' => $validatedCourseData['title'],
                'description' => $validatedCourseData['description'],
                'price' => $validatedCourseData['price'] ?? 0,
                'additional_description' => $validatedCourseData['additional_description'] ?? null,
                'recomendations' => $validatedCourseData['recomendations'] ?? null,
                'certificate_id' => $validatedCourseData['certificates'] ?? null,
                'industry_id' => $validatedCourseData['industry'] ?? null,
                'course_type_id' => $validatedCourseData['course_type'] ?? null,
                'topic_id' => $validatedCourseData['topic'] ?? null,
                'status' => 'published',
            ];

            $course = $this->courseService->create($courseDataToCreate);

            $createdVideos = [];
            if ($request->has('videos') && is_array($request->input('videos'))) {
                foreach ($request->input('videos') as $index => $videoDataInput) {
                    $videoFile = $request->file("videos.{$index}.videoFile");
                    $videoPath = null;
                    $thumbnailPath = null;

                    if ($videoFile) {
                        $videoTargetDirectory = 'uploads/course_' . $course->id . '_videos';
                        // Ensure the directory exists
                        if (!File::isDirectory(public_path($videoTargetDirectory))) {
                            File::makeDirectory(public_path($videoTargetDirectory), 0755, true, true);
                        }
                        $videoFileName = time() . '_' . Str::slug(pathinfo($videoFile->getClientOriginalName(), PATHINFO_FILENAME)) . '.' . $videoFile->getClientOriginalExtension();
                        try {
                            $videoFile->move(public_path($videoTargetDirectory), $videoFileName);
                        } catch (\Throwable $e) {
                            throw $e;
                        }
                        $videoPath = $videoTargetDirectory . '/' . $videoFileName;
                    }

                    if ($request->hasFile("videos.{$index}.thumbnailFile")) {
                        $thumbnailFile = $request->file("videos.{$index}.thumbnailFile");
                        $thumbTargetDirectory = 'uploads/course_' . $course->id . '_video_thumbnails';
                        // Ensure the directory exists
                        if (!File::isDirectory(public_path($thumbTargetDirectory))) {
                            File::makeDirectory(public_path($thumbTargetDirectory), 0755, true, true);
                        }
                        $thumbFileName = time() . '_' . Str::slug(pathinfo($thumbnailFile->getClientOriginalName(), PATHINFO_FILENAME)) . '.' . $thumbnailFile->getClientOriginalExtension();
                        try {
                            $thumbnailFile->move(public_path($thumbTargetDirectory), $thumbFileName);
                        } catch (\Throwable $e) {
                            throw $e;
                        }
                        $thumbnailPath = $thumbTargetDirectory . '/' . $thumbFileName;
                    }

                    $videoModel = resolve(\App\Services\VideoService::class);
                    $newVideo = $videoModel->create([
                        'course_id' => $course->id,
                        'title' => $videoDataInput['title'],
                        'description' => $videoDataInput['description'],
                        'takeaway_notes' => $videoDataInput['takeaway_notes'] ?? null,
                        'video_url' => $videoPath,
                        'thumbnail_url' => $thumbnailPath,
                        'order' => $videoDataInput['order'],
                        'duration' => $videoDataInput['duration_in_seconds'] ?? null,
                    ]);
                    $createdVideos[] = $newVideo;

                    $this->syncVideoQuiz($newVideo, $videoDataInput['quiz'] ?? null);
                }
            }

            if ($request->has('quiz')) {
                $this->syncCourseQuiz($course, $validatedQuizData['quiz']);
            }

            DB::commit();

            // Send email notification to all students
            $students = User::where('type', 'student')->get();
            foreach ($students as $student) {
                if ($student->canReceiveEmail('receives_new_course_notification_emails')) {
                    try {
                        Mail::to($student->email)->send(new NewCourseNotification($course));
                    } catch (\Exception $e) {
                        \Illuminate\Support\Facades\Log::error("Failed to send new course notification email to {$student->email}: " . $e->getMessage());
                    }
                }
            }

            return redirect('/coursess')->with('success', 'Course and videos created successfully!');

        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::warning('Course with videos validation failed', [
                'user_id' => $request->user()->id ?? null,
                'title' => $request->input('title'),
                'errors' => $e->errors(),
            ]);
            DB::rollBack();
            return redirect()->back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            Log::error('Course with videos creation failed', [
                'user_id' => $request->user()->id ?? null,
                'title' => $request->input('title'),
                'message' => $e->getMessage(),
            ]);
            DB::rollBack();
            return redirect()->back()->withInput()->with('error', 'Failed to create course and videos. Please try again.');
        }
    }

    public function updateWithVideos(Request $request, Course $course): RedirectResponse
    {
        $this->authorizeCourseOwner($request, $course);

        $validatedCourseData = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'nullable|numeric|min:0',
            'additional_description' => 'nullable|string',
            'recomendations' => 'nullable|string',
            'certificates' => 'nullable|exists:course_certificates,id',
            'industry' => 'nullable|exists:course_industries,id',
            'course_type' => 'nullable|exists:course_types,id',
            'topic' => 'nullable|exists:topics,id',
        ]);

        $request->validate([
            'videos' => 'present|array',
            'videos.*.id' => 'nullable|exists:videos,id',
            'videos.*.title' => 'required_with:videos|string|max:255',
            'videos.*.description' => 'required_with:videos|string',
            'videos.*.takeaway_notes' => 'nullable|string',
            'videos.*.videoFile' => 'nullable|file|mimes:mp4,mov,ogg,qt|max:512000',
            'videos.*.thumbnailFile' => 'nullable|image|mimes:jpg,jpeg,png,gif|max:2048',
            'videos.*.order' => 'required_with:videos|integer|min:1',
            'videos.*.duration_in_seconds' => 'nullable|integer|min:0',
            // Per-video quiz validation - if quiz data exists, it must be complete
            'videos.*.quiz' => 'nullable|array',
            'videos.*.quiz.title' => 'required_with:videos.*.quiz|string|max:255',
            'videos.*.quiz.description' => 'nullable|string',
            'videos.*.quiz.questions' => 'required_with:videos.*.quiz|array|min:1',
            'videos.*.quiz.questions.*.question_text' => 'required_with:videos.*.quiz.questions|string',
            'videos.*.quiz.questions.*.answers' => 'required_with:videos.*.quiz.questions|array|min:2',
            'videos.*.quiz.questions.*.answers.*.answer_text' => 'required_with:videos.*.quiz.questions.*.answers|string',
            'videos.*.quiz.questions.*.answers.*.is_correct' => 'boolean',
        ]);

        $validatedQuizData = [];
        if ($request->has('quiz')) {
            $validatedQuizData = $request->validate([
                'quiz' => 'sometimes|array',
                'quiz.title' => 'required_with:quiz|string|max:255',
                'quiz.description' => 'nullable|string',
                'quiz.questions' => 'required_with:quiz|array|min:1',
                'quiz.questions.*.question_text' => 'required_with:quiz.questions|string',
                'quiz.questions.*.answers' => 'required_with:quiz.questions|array|min:2',
                'quiz.questions.*.answers.*.answer_text' => 'required_with:quiz.questions.*.answers|string',
                'quiz.questions.*.answers.*.is_correct' => 'boolean',
            ]);
        }

        $request->validate([
            'removed_video_ids' => 'sometimes|array',
            'removed_video_ids.*' => 'integer|exists:videos,id',
        ]);

        DB::beginTransaction();

        try {
            $courseDataToUpdate = [
                'title' => $validatedCourseData['title'],
                'description' => $validatedCourseData['description'],
                'price' => $validatedCourseData['price'] ?? 0,
                'additional_description' => $validatedCourseData['additional_description'] ?? null,
                'recomendations' => $validatedCourseData['recomendations'] ?? null,
                'certificate_id' => $validatedCourseData['certificates'] ?? null,
                'industry_id' => $validatedCourseData['industry'] ?? null,
                'course_type_id' => $validatedCourseData['course_type'] ?? null,
                'topic_id' => $validatedCourseData['topic'] ?? null,
                'status' => 'published', // Set status to published when updating/publishing
            ];

            $course->update($courseDataToUpdate);

            $videoService = resolve(\App\Services\VideoService::class);
            $processedVideoIds = collect();
            $incomingVideos = $request->input('videos', []);

            foreach ($incomingVideos as $index => $videoDataInput) {
                $videoId = $videoDataInput['id'] ?? null;
                $videoFile = $request->file("videos.$index.videoFile");
                $thumbnailFile = $request->file("videos.$index.thumbnailFile");
                $videoPath = null;
                $thumbnailPath = null;

                if ($videoFile) {
                    $videoTargetDirectory = 'uploads/course_' . $course->id . '_videos';
                    if (!File::isDirectory(public_path($videoTargetDirectory))) {
                        File::makeDirectory(public_path($videoTargetDirectory), 0755, true, true);
                    }
                    $videoFileName = time() . '_' . Str::slug(pathinfo($videoFile->getClientOriginalName(), PATHINFO_FILENAME)) . '.' . $videoFile->getClientOriginalExtension();
                    $videoFile->move(public_path($videoTargetDirectory), $videoFileName);
                    $videoPath = $videoTargetDirectory . '/' . $videoFileName;
                }

                if ($thumbnailFile) {
                    $thumbTargetDirectory = 'uploads/course_' . $course->id . '_video_thumbnails';
                    if (!File::isDirectory(public_path($thumbTargetDirectory))) {
                        File::makeDirectory(public_path($thumbTargetDirectory), 0755, true, true);
                    }
                    $thumbFileName = time() . '_' . Str::slug(pathinfo($thumbnailFile->getClientOriginalName(), PATHINFO_FILENAME)) . '.' . $thumbnailFile->getClientOriginalExtension();
                    $thumbnailFile->move(public_path($thumbTargetDirectory), $thumbFileName);
                    $thumbnailPath = $thumbTargetDirectory . '/' . $thumbFileName;
                }

                if (!$videoId) {
                    if (!$videoFile) {
                        throw ValidationException::withMessages([
                            "videos.$index.videoFile" => 'Video file is required when adding a new video.',
                        ]);
                    }

                    $newVideo = $videoService->create([
                        'course_id' => $course->id,
                        'title' => $videoDataInput['title'],
                        'description' => $videoDataInput['description'],
                        'takeaway_notes' => $videoDataInput['takeaway_notes'] ?? null,
                        'video_url' => $videoPath,
                        'thumbnail_url' => $thumbnailPath,
                        'order' => $videoDataInput['order'],
                        'duration' => $videoDataInput['duration_in_seconds'] ?? null,
                    ]);

                    $processedVideoIds->push($newVideo->id);
                    $this->syncVideoQuiz($newVideo, $videoDataInput['quiz'] ?? null);
                    continue;
                }

                $existingVideo = $course->videos()->where('id', $videoId)->firstOrFail();

                $updatePayload = [
                    'title' => $videoDataInput['title'],
                    'description' => $videoDataInput['description'],
                    'takeaway_notes' => $videoDataInput['takeaway_notes'] ?? null,
                    'order' => $videoDataInput['order'],
                    'duration' => $videoDataInput['duration_in_seconds'] ?? $existingVideo->duration,
                ];

                if ($videoPath) {
                    $updatePayload['video_url'] = $videoPath;
                }

                if ($thumbnailPath) {
                    $updatePayload['thumbnail_url'] = $thumbnailPath;
                }

                $videoService->update($existingVideo->id, $updatePayload);
                $existingVideo->refresh();
                $processedVideoIds->push($existingVideo->id);

                $this->syncVideoQuiz($existingVideo, $videoDataInput['quiz'] ?? null);
            }

            $existingVideoIds = $course->videos()->pluck('id');
            $explicitRemovedIds = collect($request->input('removed_video_ids', []));
            $idsToDelete = $existingVideoIds->diff($processedVideoIds)->merge($explicitRemovedIds)->unique();

            if ($idsToDelete->isNotEmpty()) {
                $videosToDelete = $course->videos()->whereIn('id', $idsToDelete)->get();
                foreach ($videosToDelete as $videoToDelete) {
                    $this->syncVideoQuiz($videoToDelete, null);
                    $videoToDelete->delete();
                }
            }

            if (!empty($validatedQuizData)) {
                $course->refresh();
                $this->syncCourseQuiz($course, $validatedQuizData['quiz'] ?? null);
            }

            DB::commit();

            return redirect('/coursess')->with('success', 'Course updated successfully!');
        } catch (ValidationException $e) {
            DB::rollBack();
            throw $e;
        } catch (\Throwable $e) {
            Log::error('Course update with videos failed', [
                'course_id' => $course->id,
                'message' => $e->getMessage(),
            ]);
            DB::rollBack();
            return redirect()->back()->withInput()->with('error', 'Failed to update course and videos. Please try again.');
        }
    }

    public function show(int $courseId)
    {
        $course = Course::withTrashed()->findOrFail($courseId);

        if ($course->trashed()) {
            return Inertia::render('Course/Detail', [
                'course' => null,
                'isPurchased' => false,
                'unavailableMessage' => 'This course is not available or has been temparly deleted by the instructor.',
            ]);
        }

        // If the user is not the course author, require a paid purchase before viewing details.
        $isPurchased = false;
        if (Auth::check()) {
            $isPurchased = Invoice::where('user_id', Auth::id())
                                  ->where('payment_status', 'paid')
                                  ->whereHas('details', function ($query) use ($course) {
                                      $query->where('course_id', $course->id);
                                  })
                                  ->exists();
        }

        if (!Auth::check() || (Auth::id() !== $course->user_id && !$isPurchased)) {
            return redirect()->route('purchase-course.show', ['course_id' => $course->id]);
        }

        // Eager load relationships you might need
        $course->load([
            'courseType',
            'industry',
            'certificate',
            'topic',
            'user',
            'sections' => function ($q) {
                $q->orderBy('order');
            },
            'sections.videos' => function ($q) {
                $q->orderBy('order');
            },
        ]);
        $course->load('videos'); // keep for first_video_thumbnail_url and flat list fallback

        // Prepare the data for the view
        $courseData = [
            'id' => $course->id,
            'user_id' => $course->user_id, // Add the author's ID
            'title' => $course->title,
            'price' => $course->price,
            'description' => $course->description, // Assuming you have a description field
            'type' => $course->courseType ? $course->courseType->name : 'N/A',
            'industry_name' => $course->industry ? $course->industry->name : 'N/A', // Get name from relationship
            'certificate_name' => $course->certificate ? $course->certificate->name : 'N/A', // Get name from relationship
            'author' => $course->user ? $course->user->name : 'Placeholder Author',
            'instructor' => $course->user ? [
                'id' => $course->user->id,
                'name' => $course->user->name,
                'profile_photo_url' => $course->user->profile_photo_url ?? null,
                'type' => $course->user->type ?? 'instructor',
            ] : null,
            'additional_description' => $course->additional_description,
            'recomendations' => $course->recomendations,
            'topic_name' => $course->topic ? $course->topic->name : 'N/A', // Get name from relationship
            'first_video_thumbnail_url' => null, // Initialize
            'videos' => $course->videos->map(function ($video) {
                return [
                    'id' => $video->id,
                    'title' => $video->title,
                    'thumbnail_url' => $video->thumbnail_url ? asset($video->thumbnail_url) : null,
                    'video_url' => $video->video_url ? asset($video->video_url) : null,
                    'order' => $video->order,
                ];
            })->sortBy('order')->values(),
            'sections' => $course->sections->map(function ($section) {
                return [
                    'id' => $section->id,
                    'title' => $section->title,
                    'order' => $section->order,
                    'videos' => $section->videos->map(function ($video) {
                        return [
                            'id' => $video->id,
                            'title' => $video->title,
                            'thumbnail_url' => $video->thumbnail_url ? asset($video->thumbnail_url) : null,
                            'video_url' => $video->video_url ? asset($video->video_url) : null,
                            'order' => $video->order,
                        ];
                    })->values(),
                ];
            })->values(),
        ];

        if ($course->videos->isNotEmpty() && $course->videos->first()->thumbnail_url) {
            $courseData['first_video_thumbnail_url'] = asset($course->videos->first()->thumbnail_url);
        }
        $courseData['lessons_count'] = $course->videos->count();

        // Count how many videos the current user has completed in this course
        $completedCount = 0;
        if (Auth::check()) {
            $videoIds = $course->videos->pluck('id');
            $completedCount = Progress::where('user_id', Auth::id())
                ->whereIn('video_id', $videoIds)
                ->where('completed', true)
                ->count();
        }

        return Inertia::render('Course/Detail', [
            'course' => $courseData,
            'isPurchased' => $isPurchased,
            'completedCount' => $completedCount,
            'unavailableMessage' => null,
        ]);
    }

    public function showFeedback(Course $course)
    {
        // Authorization: Ensure the logged-in user is the author of the course.
        if (auth()->id() !== $course->user_id) {
            abort(403, 'Unauthorized action.');
        }

        $reviews = Review::where('course_id', $course->id)
                         ->with('user') // Eager load the user who made the review
                         ->orderBy('id', 'desc') // Order by ID descending
                         ->paginate(10);

        return Inertia::render('Course/Feedback', [
            'course' => $course,
            'reviews' => $reviews,
        ]);
    }

    public function update(CourseRequest $request, int $id): RedirectResponse
    {
        $course = Course::findOrFail($id);
        $this->authorizeCourseOwner($request, $course);

        $this->courseService->update($course->id, $request->validated());

        return redirect()->route('coursess')
            ->with('success', 'Course updated successfully!');
    }

    public function destroy(Request $request, int $course): RedirectResponse
    {
        $courseModel = Course::findOrFail($course);
        $this->authorizeCourseOwner($request, $courseModel);

        $this->courseService->delete($courseModel->id);

        return redirect()->route('coursess')
            ->with('success', 'Course deleted successfully!');
    }

    public function restore(Request $request, int $course): RedirectResponse
    {
        $courseModel = Course::withTrashed()->findOrFail($course);
        $this->authorizeCourseOwner($request, $courseModel);

        if ($courseModel->trashed()) {
            $courseModel->restore();
        }

        return redirect()->route('coursess')
            ->with('success', 'Course restored successfully!');
    }

    public function play(Request $request, Course $course, $videoId = null)
    {
        // Only allow access if user is the course author or has purchased the course
        $isPurchased = false;
        if (Auth::check()) {
            $isPurchased = Invoice::where('user_id', Auth::id())
                                  ->where('payment_status', 'paid')
                                  ->whereHas('details', function ($query) use ($course) {
                                      $query->where('course_id', $course->id);
                                  })
                                  ->exists();
        }

        if (!Auth::check() || (Auth::id() !== $course->user_id && !$isPurchased)) {
            return redirect()->route('purchase-course.show', ['course_id' => $course->id]);
        }

        // Dispatch the CourseViewed event
        if (Auth::check()) {
            event(new CourseViewed(Auth::user(), $course));
        }

        $course->load([
            'videos' => function ($query) {
                $query->orderBy('order', 'asc');
            },
            'videos.quiz.questions.answers',
            'sections' => function ($q) {
                $q->orderBy('order');
            },
            'sections.videos' => function ($q) {
                $q->orderBy('order');
            },
            'comments' => function ($query) {
                $query->with('user')->latest(); // Eager load user for comments and order by latest
            },
            'user', // Eager load the course instructor/user
            'courseType', // Eager load courseType if not already loaded or needed directly
            'reviews', // Eager load reviews for rating calculation
        ]);

        // Calculate average rating and reviews count
        $reviews = $course->reviews;
        $average_rating = $reviews->isNotEmpty() ? $reviews->avg('rating') : 0;
        $reviews_count = $reviews->count();

        // Calculate total course duration
        $totalDurationSeconds = $course->videos->sum('duration');
        $hours = floor($totalDurationSeconds / 3600);
        $minutes = round(($totalDurationSeconds % 3600) / 60);
        $formattedDuration = [];
        if ($hours > 0) {
            $formattedDuration[] = "{$hours}h";
        }
        if ($minutes > 0) {
            $formattedDuration[] = "{$minutes}m";
        }
        $total_duration = implode(' ', $formattedDuration);

        $videosData = $course->videos->map(function ($video) {
            return [
                'id' => $video->id,
                'title' => $video->title,
                'description' => $video->description, // Ensure Video model has description
                'takeaway_notes' => $video->takeaway_notes,
                'video_url' => $video->video_url ? asset($video->video_url) : null,
                'thumbnail_url' => $video->thumbnail_url ? asset($video->thumbnail_url) : null,
                'order' => $video->order,
                'quiz' => $video->quiz ? [
                    'id' => $video->quiz->id,
                    'title' => $video->quiz->title,
                    'description' => $video->quiz->description,
                    'questions' => $video->quiz->questions->map(function ($q) {
                        return [
                            'id' => $q->id,
                            'question_text' => $q->question_text,
                            'answers' => $q->answers->map(function ($a) {
                                return [
                                    'id' => $a->id,
                                    'answer_text' => $a->answer_text,
                                ];
                            }),
                        ];
                    }),
                ] : null,
                // Add other video properties if needed
            ];
        });

        $courseData = [
            'id' => $course->id,
            'title' => $course->title,
            'description' => $course->description,
            'type' => $course->courseType ? $course->courseType->name : 'N/A',
            'updated_at' => $course->updated_at->format('M d, Y'),
            'additional_description' => $course->additional_description,
            'recommendations' => $course->recomendations,
            'topic_name' => $course->topic ? $course->topic->name : 'N/A', // Get name from relationship
            'user' => $course->user, // user is already loaded via $course->load('user')
            'average_rating' => round($average_rating, 1),
            'reviews_count' => $reviews_count,
            'total_duration' => $total_duration,
            // 'profile_picture' => $course->user->profile_picture, // This can be accessed via course.user.profile_picture in Vue
            'comments' => $course->comments->map(function ($comment) { // Map comments to include necessary data
                return [
                    'id' => $comment->id,
                    'body' => $comment->body,
                    'created_at' => $comment->created_at->toDateTimeString(), // Or format as needed
                    'user' => [
                        'id' => $comment->user->id,
                        'name' => $comment->user->name,
                        'profile_photo_url' => $comment->user->profile_photo_url ?? null // Assuming profile_photo_url exists on User model
                    ]
                ];
            }),
            'videos' => $videosData,
            'sections' => $course->sections->map(function ($section) {
                return [
                    'id' => $section->id,
                    'title' => $section->title,
                    'order' => $section->order,
                    'videos' => $section->videos->map(function ($v) {
                        return [
                            'id' => $v->id,
                            'title' => $v->title,
                            'order' => $v->order,
                        ];
                    })->values(),
                ];
            })->values(),
            // Add other course properties if needed by the player page
        ];

        // Determine initial video ID to play
        $initialVideoToPlayId = $videoId;
        if (!$initialVideoToPlayId && $videosData->isNotEmpty()) {
            $initialVideoToPlayId = $videosData->first()['id'];
        }

        return Inertia::render('Course/Player', [
            'course' => $courseData,
            'initialVideoId' => $initialVideoToPlayId,
        ]);
    }

    // Method to display courses for the authenticated user
    public function myCourses(Request $request): InertiaResponse
    {
        $user = $request->user();
        $coursesData = collect(); // Default to an empty collection

        if ($user) {
            $courses = \App\Models\Course::withTrashed()
                ->where('user_id', $user->id)
                ->with(['videos' => function ($query) {
                    $query->orderBy('order', 'asc');
                }, 'courseType']) // Eager load videos and courseType
                ->latest()
                ->get();

            $coursesData = $courses->map(function ($course) {
                $firstVideoThumbnailUrl = null;
                if ($course->videos->isNotEmpty() && $course->videos->first()->thumbnail_url) {
                    $firstVideoThumbnailUrl = asset($course->videos->first()->thumbnail_url);
                }
                return [
                    'id' => $course->id,
                    'title' => $course->title,
                    'description' => $course->description, // Ensure description is passed
                    'thumbnail_url' => $course->thumbnail ? asset($course->thumbnail) : null, // Assuming 'thumbnail' is the field for course-specific thumbnail
                    'first_video_thumbnail_url' => $firstVideoThumbnailUrl,
                    'type' => $course->courseType ? $course->courseType->name : 'N/A', // If you use course type.
                    'author' => $course->user ? $course->user->name : 'Placeholder Author', // Or however you get the author
                    'is_favorited' => $course->is_favorited, // Explicitly include is_favorited
                    'price' => $course->price,
                    'status' => $course->status ?? 'draft',
                    'category_id' => $course->category_id,
                    'instructor_id' => $course->instructor_id,
                    'thumbnail' => $course->thumbnail,
                    'additional_description' => $course->additional_description,
                    'recomendations' => $course->recomendations,
                    'topic_id' => $course->topic_id,
                    'deleted_at' => $course->deleted_at,
                    // Include other necessary course properties
                ];
            });
        }

        return Inertia::render('Courses/myCourses', [
            'courses' => $coursesData,
        ]);
    }

    public function related(Course $course)
    {
        if (!$course->topic_id) {
            return response()->json([]);
        }

        $relatedCourses = Course::where('topic_id', $course->topic_id)
            ->where('id', '!=', $course->id)
            ->where('status', 'published') // Only show published courses
            ->with(['user', 'videos'])
            ->addSelect(['learners_count' => Progress::selectRaw('count(distinct user_id)')
                ->join('videos', 'videos.id', '=', 'progress.video_id')
                ->whereColumn('videos.course_id', 'courses.id')
            ])
            ->latest()
            ->take(4)
            ->get();

        $formattedCourses = $relatedCourses->map(function ($relatedCourse) {
            $thumbnail_url = $relatedCourse->thumbnail ? asset($relatedCourse->thumbnail) : null;
            if (!$thumbnail_url && $relatedCourse->videos->isNotEmpty() && $relatedCourse->videos->first()->thumbnail_url) {
                $thumbnail_url = asset($relatedCourse->videos->first()->thumbnail_url);
            }

            $totalDurationSeconds = $relatedCourse->videos->sum('duration');
            $hours = floor($totalDurationSeconds / 3600);
            $minutes = round(($totalDurationSeconds % 3600) / 60);

            if ($hours == 0 && $minutes == 0 && $totalDurationSeconds > 0) {
                $minutes = 1;
            }

            $formattedDuration = [];
            if ($hours > 0) {
                $formattedDuration[] = "{$hours}h";
            }
            if ($minutes > 0) {
                $formattedDuration[] = "{$minutes}m";
            }

            return [
                'id' => $relatedCourse->id,
                'title' => $relatedCourse->title,
                'user' => [
                    'name' => $relatedCourse->user->name,
                ],
                'thumbnail_url' => $thumbnail_url,
                'total_duration' => implode(' ', $formattedDuration),
                'learners_count' => number_format($relatedCourse->learners_count),
                'is_popular' => $relatedCourse->learners_count > 2,
                'is_favorited' => $relatedCourse->is_favorited, // Relies on the is_favorited accessor
            ];
        });

        return response()->json($formattedCourses);
    }

    /**
     * Store detailed logs for course video upload errors originating from the frontend.
     *
     * This is intended to capture failures that happen before the request
     * reaches Laravel successfully or when the frontend detects upload issues.
     */
    public function logUploadError(Request $request): JsonResponse
    {
        $payload = $request->validate([
            'source' => 'required|string|max:50', // e.g. "frontend" | "backend"
            'message' => 'required|string',
            'course_id' => 'nullable|integer|exists:courses,id',
            'video_index' => 'nullable|integer',
            'file_name' => 'nullable|string|max:255',
            'extra' => 'nullable|array',
        ]);

        Log::error('Course video upload error', [
            'source' => $payload['source'],
            'message' => $payload['message'],
            'course_id' => $payload['course_id'] ?? null,
            'video_index' => $payload['video_index'] ?? null,
            'file_name' => $payload['file_name'] ?? null,
            'extra' => $payload['extra'] ?? [],
            'user_id' => optional($request->user())->id,
            'ip' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        return response()->json([
            'success' => true,
        ]);
    }

    private function getCourseFormOptions(): array
    {
        $certificates = CourseCertificate::all()->map(function ($certificate) {
            return [
                'value' => $certificate->id,
                'text' => $certificate->name,
            ];
        });

        $industries = CourseIndustry::all()->map(function ($industry) {
            return [
                'value' => $industry->id,
                'text' => $industry->name,
            ];
        });

        $courseTypes = CourseType::all()->map(function ($courseType) {
            return [
                'value' => $courseType->id,
                'text' => $courseType->name,
            ];
        });

        $topics = CourseTopic::all()->map(function ($topic) {
            return [
                'value' => $topic->id,
                'text' => $topic->name,
            ];
        });

        return [
            'certificates' => $certificates,
            'industries' => $industries,
            'courseTypes' => $courseTypes,
            'topics' => $topics,
        ];
    }

    private function syncVideoQuiz(Video $video, ?array $quizPayload): void
    {
        if (!$quizPayload || !is_array($quizPayload)) {
            if ($video->quiz) {
                $video->quiz->questions()->each(function ($question) {
                    $question->answers()->delete();
                });
                $video->quiz->questions()->delete();
                $video->quiz()->delete();
            }
            return;
        }

        // For draft saves, allow empty questions array
        $isDraftSave = empty($quizPayload['questions']) || count($quizPayload['questions']) === 0;
        
        $quizInput = ['quiz' => $quizPayload];
        $validationRules = [
            'quiz.title' => 'required|string|max:255',
            'quiz.description' => 'nullable|string',
            'quiz.questions' => 'present|array',
        ];
        
        // Only require questions if not a draft save (has questions)
        if (!$isDraftSave) {
            $validationRules['quiz.questions'] = 'present|array|min:1';
            $validationRules['quiz.questions.*.question_text'] = 'required|string';
            $validationRules['quiz.questions.*.answers'] = 'present|array|min:2';
            $validationRules['quiz.questions.*.answers.*.answer_text'] = 'required|string';
            $validationRules['quiz.questions.*.answers.*.is_correct'] = 'boolean';
        } else {
            // For draft, allow nullable questions
            $validationRules['quiz.questions'] = 'nullable|array';
        }
        
        $quizValidator = Validator::make($quizInput, $validationRules);
        $quizData = $quizValidator->validate()['quiz'];

        if ($video->quiz) {
            $video->quiz->questions()->each(function ($question) {
                $question->answers()->delete();
            });
            $video->quiz->questions()->delete();
            $video->quiz()->delete();
        }

        $quiz = $video->quiz()->create([
            'course_id' => $video->course_id,
            'title' => $quizData['title'],
            'description' => $quizData['description'] ?? null,
        ]);

        // Only create questions if they exist and are not empty
        if (!empty($quizData['questions']) && is_array($quizData['questions'])) {
            foreach ($quizData['questions'] as $questionData) {
                // Skip empty questions
                if (empty($questionData['question_text']) || empty($questionData['answers'])) {
                    continue;
                }
                
                $question = $quiz->questions()->create([
                    'question_text' => $questionData['question_text'],
                ]);

                if (!empty($questionData['answers']) && is_array($questionData['answers'])) {
                    foreach ($questionData['answers'] as $answerData) {
                        // Skip empty answers
                        if (empty($answerData['answer_text'])) {
                            continue;
                        }
                        
                        $question->answers()->create([
                            'answer_text' => $answerData['answer_text'],
                            'is_correct' => $answerData['is_correct'] ?? false,
                        ]);
                    }
                }
            }
        }
    }

    private function syncCourseQuiz(Course $course, ?array $quizPayload): void
    {
        if (!$quizPayload || !is_array($quizPayload)) {
            $course->quizzes()->each(function ($quiz) {
                $quiz->questions()->each(function ($question) {
                    $question->answers()->delete();
                });
                $quiz->questions()->delete();
                $quiz->delete();
            });
            return;
        }

        $quizInput = ['quiz' => $quizPayload];
        $quizValidator = Validator::make($quizInput, [
            'quiz.title' => 'required|string|max:255',
            'quiz.description' => 'nullable|string',
            'quiz.questions' => 'present|array|min:1',
            'quiz.questions.*.question_text' => 'required|string',
            'quiz.questions.*.answers' => 'present|array|min:2',
            'quiz.questions.*.answers.*.answer_text' => 'required|string',
            'quiz.questions.*.answers.*.is_correct' => 'boolean',
        ]);
        $quizData = $quizValidator->validate()['quiz'];

        $course->quizzes()->each(function ($quiz) {
            $quiz->questions()->each(function ($question) {
                $question->answers()->delete();
            });
            $quiz->questions()->delete();
            $quiz->delete();
        });

        $quiz = $course->quizzes()->create([
            'title' => $quizData['title'],
            'description' => $quizData['description'] ?? null,
        ]);

        foreach ($quizData['questions'] as $questionData) {
            $question = $quiz->questions()->create([
                'question_text' => $questionData['question_text'],
            ]);

            foreach ($questionData['answers'] as $answerData) {
                $question->answers()->create([
                    'answer_text' => $answerData['answer_text'],
                    'is_correct' => $answerData['is_correct'] ?? false,
                ]);
            }
        }
    }

    public function organizeVideoNotes(Request $request, int $videoId): JsonResponse
    {
        $video = Video::findOrFail($videoId);
        $rawNotes = $request->input('notes', $video->takeaway_notes);

        if (!$rawNotes || trim($rawNotes) === '') {
            return response()->json(['message' => 'No notes provided for organization.'], 422);
        }

        try {
            $response = Http::withToken(env('OPENAI_API_KEY'))
                ->post('https://api.openai.com/v1/chat/completions', [
                    'model' => 'gpt-4o-mini',
                    'messages' => [
                        [
                            'role' => 'system',
                            'content' => 'You are an expert technical writer. Clean, clarify, and slightly expand course video takeaway notes. Output strictly in Markdown as: numbered H2 section headings (## 1. Heading), followed by * bullet lists with short, actionable points and occasional sub-bullets. Briefly define key terms. Remove redundancy, fix grammar, keep friendly/professional tone. No code fences. No HTML. Max ~4000 characters.'
                        ],
                        [
                            'role' => 'user',
                            'content' => "Please clean up, expand slightly, and organize the following notes using numbered Markdown headings and * bullet points. Keep it concise and actionable.\n\n" . $rawNotes,
                        ],
                    ],
                    'temperature' => 0.2,
                ]);

            if (!$response->ok()) {
                return response()->json(['message' => 'Failed to organize notes.'], 500);
            }

            $data = $response->json();
            $organized = data_get($data, 'choices.0.message.content');

            if (!$organized) {
                return response()->json(['message' => 'Failed to parse organized notes.'], 500);
            }

            $video->takeaway_notes = $organized;
            $video->save();

            return response()->json([
                'video_id' => $video->id,
                'takeaway_notes' => $video->takeaway_notes,
            ]);
        } catch (\Throwable $e) {
            return response()->json(['message' => 'Unexpected error while organizing notes.'], 500);
        }
    }

    private function authorizeCourseOwner(Request $request, Course $course): void
    {
        if ($request->user()?->id !== $course->user_id) {
            abort(403, 'Unauthorized action.');
        }
    }
}
