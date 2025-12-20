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
use App\Models\Course;
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
            return redirect()->route('dashboard')
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
            'videos.quiz.questions.answers',
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
                'courseVideos' => $courseVideos,
                'courseQuiz' => $courseQuiz,
                'isEditing' => true,
            ]
        ));
    }

    public function storeWithVideos(Request $request): RedirectResponse
    {

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

        $validatedVideosData = $request->validate([
            'videos' => 'present|array',
            'videos.*.title' => 'required_with:videos|string|max:255',
            'videos.*.description' => 'required_with:videos|string',
            'videos.*.takeaway_notes' => 'nullable|string',
            'videos.*.videoFile' => 'required_with:videos|file|mimes:mp4,mov,ogg,qt|max:512000',
            'videos.*.thumbnailFile' => 'nullable|image|mimes:jpg,jpeg,png,gif|max:2048',
            'videos.*.order' => 'required_with:videos|integer',
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
                    Mail::to($student->email)->send(new NewCourseNotification($course));
                }
            }

            return redirect()->route('coursess')->with('success', 'Course and videos created successfully!');

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

            return redirect()->route('coursess')->with('success', 'Course updated successfully!');
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

        // Eager load relationships you might need
        $course->load('courseType', 'videos', 'industry', 'certificate', 'topic', 'user'); // Added 'industry' and 'certificate'

        $isPurchased = false;
        if (Auth::check()) {
            // Check if a paid invoice exists for the user that has a detail record for this course
            $isPurchased = Invoice::where('user_id', Auth::id())
                                  ->where('payment_status', 'paid') // or 'completed' depending on your status values
                                  ->whereHas('details', function ($query) use ($course) {
                                      $query->where('course_id', $course->id);
                                  })
                                  ->exists();
        }

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
            'author' => $course->user ? $course->user->name : 'Placeholder Author', // Or however you get the author
            'additional_description' => $course->additional_description,
            'recomendations' => $course->recomendations,
            'topic_name' => $course->topic ? $course->topic->name : 'N/A', // Get name from relationship
            'first_video_thumbnail_url' => null, // Initialize
            'videos' => $course->videos->map(function ($video) {
                return [
                    'id' => $video->id,
                    'title' => $video->title,
                    'thumbnail_url' => $video->thumbnail_url ? asset($video->thumbnail_url) : null,
                    'video_url' => $video->video_path ? asset($video->video_path) : null, // Assuming video_path stores the path
                    'order' => $video->order,
                ];
            })->sortBy('order')->values(), // Ensure videos are ordered and keys are reset
        ];

        if ($course->videos->isNotEmpty() && $course->videos->first()->thumbnail_url) {
            $courseData['first_video_thumbnail_url'] = asset($course->videos->first()->thumbnail_url);
        }

        return Inertia::render('Course/Detail', [
            'course' => $courseData,
            'isPurchased' => $isPurchased,
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
        // Dispatch the CourseViewed event
        if (Auth::check()) {
            event(new CourseViewed(Auth::user(), $course));
        }

        $course->load([
            'videos' => function ($query) {
                $query->orderBy('order', 'asc');
            },
            'videos.quiz.questions.answers',
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
                    'status' => $course->status,
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
