<?php

namespace App\Http\Controllers;

use App\Models\Topic;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Course;
use Illuminate\Support\Facades\Auth;
use App\Models\Invoice;
use App\Models\Progress;

class TopicController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // index method removed
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'is_trending' => 'required|boolean',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $data = $request->only('name', 'is_trending');

        if ($request->hasFile('logo')) {
            $logoName = time().'.'.$request->logo->extension();
            $request->logo->move(public_path('images/topicsLogo'), $logoName);
            $data['logo_url'] = '/images/topicsLogo/'.$logoName;
        }

        Topic::create($data);
        return redirect()->back();
    }

    /**
     * Display the specified resource.
     */
    public function show(Topic $topic)
    {
        $user = Auth::user();

        $courses = Course::where('topic_id', $topic->id)
            ->with(['videos', 'courseType', 'certificate', 'industry'])
            ->get()
            ->map(function ($course) use ($user) {
                $firstVideo = $course->videos->sortBy('id')->first();
                $firstVideoThumbnailUrl = null;
                if ($firstVideo && $firstVideo->thumbnail_url) {
                    $firstVideoThumbnailUrl = asset($firstVideo->thumbnail_url);
                }
                $progress = 0;
                if (Auth::check()) {
                    $userId = Auth::id();
                    $isPurchased = Invoice::where('user_id', $userId)
                        ->where('payment_status', 'paid')
                        ->whereHas('details', function ($query) use ($course) {
                            $query->where('course_id', $course->id);
                        })
                        ->exists();

                    if ($firstVideo) {
                        $videoProgress = Progress::where('user_id', $userId)
                            ->where('video_id', $firstVideo->id)
                            ->first();

                        if ($videoProgress && $firstVideo->duration > 0) {
                            if ($videoProgress->completed) {
                                $progress = 100;
                            } else {
                                $progress = ($videoProgress->watched_duration / $firstVideo->duration) * 100;
                            }
                        }
                    }
                }

                $course->progress = $progress;
                $course->is_favorited = $user->courseFavorites()->where('course_id', $course->id)->exists();
                $course->is_purchased = $isPurchased;
                $course->first_video_id = $firstVideo ? $firstVideo->id : null;
                $course->first_video_thumbnail_url = $firstVideoThumbnailUrl;
                $course->author = $course->user ? $course->user->name : 'Placeholder Author';
                $course->type = $course->coursetype->name ?? 'N/A';

                return $course;
            });

        return Inertia::render('Topic/Show', [
            'topic' => $topic,
            'courses' => $courses,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Topic $topic)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Topic $topic)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'is_trending' => 'required|boolean',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $data = $request->only('name', 'is_trending');

        if ($request->hasFile('logo')) {
            $logoName = time().'.'.$request->logo->extension();
            $request->logo->move(public_path('images/topicsLogo'), $logoName);
            $data['logo_url'] = '/images/topicsLogo/'.$logoName;
        }
        
        $topic->update($data);
        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Topic $topic)
    {
        $topic->delete();
        return redirect()->back();
    }

    public function fetchTrending()
    {
        $trendingTopics = Topic::where('is_trending', true)
                               ->take(5)
                               ->get(['id', 'name']);

        $topicsWithSlugs = $trendingTopics->map(function ($topic) {
            return [
                'id' => $topic->id,
                'name' => $topic->name,
                'slug' => \Illuminate\Support\Str::slug($topic->name) // Generate slug
            ];
        });

        return response()->json($topicsWithSlugs);
    }
}
