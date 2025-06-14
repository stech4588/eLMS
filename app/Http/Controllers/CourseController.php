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
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use Illuminate\Support\Str; // Import Str facade
use Illuminate\Support\Facades\File; // Import File facade for directory creation
use App\Models\Course;
use App\Models\Invoice;
use Illuminate\Support\Facades\Auth;

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
        $course = $this->courseService->create($request->validated());
        return redirect()->route('dashboard')
            ->with('success', 'Course created successfully!')
            ->with('course_id', $course->id);
    }

    public function create() // Or your specific method name
    {
        // Fetch data from your database
        // The ->map() is used to format the data into { value: ..., text: ... } objects
        // Adjust the query and mapping based on your actual database schema

        $certificates = CourseCertificate::all()->map(function ($certificate) {
            return [
                'value' => $certificate->id, // Or whatever unique identifier you use
                'text' => $certificate->name, // Or title, or display_name
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

        $topics = CourseTopic::all()->map(function ($topic) { // Added topics fetching
            return [
                'value' => $topic->id,
                'text' => $topic->name,
            ];
        });

        // Add this line for debugging
        return Inertia::render('addCourses/addNewCourses', [
            'certificates' => $certificates,
            'industries' => $industries,
            'courseTypes' => $courseTypes,
            'topics' => $topics, // Pass topics to the view
            // You can also pass other necessary data here
        ]);
    }

    public function storeWithVideos(Request $request): RedirectResponse
    {
        Log::info('storeWithVideos request data:', $request->all());

        $validatedCourseData = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'nullable|numeric',
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
            'videos.*.videoFile' => 'required_with:videos|file|mimes:mp4,mov,ogg,qt|max:100000',
            'videos.*.thumbnailFile' => 'nullable|image|mimes:jpg,jpeg,png,gif|max:2048',
            'videos.*.order' => 'required_with:videos|integer',
        ]);

        DB::beginTransaction();

        try {
            $courseDataToCreate = [
                'user_id' => $request->user()->id,
                'title' => $validatedCourseData['title'],
                'description' => $validatedCourseData['description'],
                'price' => $validatedCourseData['price'] ?? null,
                'additional_description' => $validatedCourseData['additional_description'] ?? null,
                'recomendations' => $validatedCourseData['recomendations'] ?? null,
                'certificate_id' => $validatedCourseData['certificates'] ?? null,
                'industry_id' => $validatedCourseData['industry'] ?? null,
                'course_type_id' => $validatedCourseData['course_type'] ?? null,
                'topic_id' => $validatedCourseData['topic'] ?? null,
            ];

            $course = $this->courseService->create($courseDataToCreate);
            Log::info('Course created:', $course->toArray());

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
                        $videoFile->move(public_path($videoTargetDirectory), $videoFileName);
                        $videoPath = $videoTargetDirectory . '/' . $videoFileName;
                        Log::info("Video file stored at: {$videoPath} (public path) for course ID: {$course->id}", ['originalName' => $videoFile->getClientOriginalName()]);
                    }

                    if ($request->hasFile("videos.{$index}.thumbnailFile")) {
                        $thumbnailFile = $request->file("videos.{$index}.thumbnailFile");
                        $thumbTargetDirectory = 'uploads/course_' . $course->id . '_video_thumbnails';
                        // Ensure the directory exists
                        if (!File::isDirectory(public_path($thumbTargetDirectory))) {
                            File::makeDirectory(public_path($thumbTargetDirectory), 0755, true, true);
                        }
                        $thumbFileName = time() . '_' . Str::slug(pathinfo($thumbnailFile->getClientOriginalName(), PATHINFO_FILENAME)) . '.' . $thumbnailFile->getClientOriginalExtension();
                        $thumbnailFile->move(public_path($thumbTargetDirectory), $thumbFileName);
                        $thumbnailPath = $thumbTargetDirectory . '/' . $thumbFileName;
                        Log::info("Thumbnail file stored at: {$thumbnailPath} (public path) for video: {$videoDataInput['title']}", ['originalName' => $thumbnailFile->getClientOriginalName()]);
                    }

                    $videoModel = resolve(\App\Services\VideoService::class);
                    $newVideo = $videoModel->create([
                        'course_id' => $course->id,
                        'title' => $videoDataInput['title'],
                        'description' => $videoDataInput['description'],
                        'video_url' => $videoPath,
                        'thumbnail_url' => $thumbnailPath,
                        'order' => $videoDataInput['order'],
                        'duration' => $videoDataInput['duration_in_seconds'] ?? null,
                    ]);
                    $createdVideos[] = $newVideo;
                    Log::info('Video created:', $newVideo->toArray());
                }
            }

            DB::commit();
            Log::info('Transaction committed.');
            return redirect()->route('coursess')->with('success', 'Course and videos created successfully!');

        } catch (\Illuminate\Validation\ValidationException $e) {
            DB::rollBack();
            Log::error('Validation error during storeWithVideos:', ['errors' => $e->errors()]);
            return redirect()->back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error during storeWithVideos:', ['message' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
            return redirect()->back()->with('error', 'Failed to create course and videos: ' . $e->getMessage());
        }
    }

    public function show(Course $course)
    {
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
        ]);
    }

    public function update(CourseRequest $request, int $id): RedirectResponse
    {
        $course = $this->courseService->update($id, $request->validated());
        return redirect()->route('dashboard')
            ->with('success', 'Course updated successfully!');
    }

    public function destroy(int $id): RedirectResponse
    {
        $this->courseService->delete($id);
        return redirect()->route('dashboard')
            ->with('success', 'Course deleted successfully!');
    }

    public function play(Request $request, Course $course, $videoId = null)
    {
        $course->load([
            'videos' => function ($query) {
                $query->orderBy('order', 'asc');
            },
            'comments' => function ($query) {
                $query->with('user')->latest(); // Eager load user for comments and order by latest
            },
            'user', // Eager load the course instructor/user
            'courseType' // Eager load courseType if not already loaded or needed directly
        ]);

        $videosData = $course->videos->map(function ($video) {
            return [
                'id' => $video->id,
                'title' => $video->title,
                'description' => $video->description, // Ensure Video model has description
                'video_url' => $video->video_url ? asset($video->video_url) : null,
                'order' => $video->order,
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
            $courses = \App\Models\Course::where('user_id', $user->id)
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
                    // Include other necessary course properties
                ];
            });
        }

        return Inertia::render('Courses/myCourses', [
            'courses' => $coursesData,
        ]);
    }
}
