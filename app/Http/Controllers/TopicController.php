<?php

namespace App\Http\Controllers;

use App\Models\Topic;
use Illuminate\Http\Request;

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
        ]);
        Topic::create($request->all());
        return redirect()->back();
    }

    /**
     * Display the specified resource.
     */
    public function show(Topic $topic)
    {
        //
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
        ]);
        $topic->update($request->all());
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
