<?php

namespace App\Http\Controllers;

use App\Models\CommunityPost;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class CommunityPostController extends Controller
{
    public function index(Request $request)
    {
        try {
            $posts = CommunityPost::with('user:id,name,type,profile_picture')
                ->whereNull('parent_id')
                ->withCount('replies')
                ->latest()
                ->paginate(10);

            return $posts;
        } catch (\Exception $e) {
            Log::error('Error fetching community posts: ' . $e->getMessage() . ' Stack trace: ' . $e->getTraceAsString());
            return response()->json([
                'message' => 'Error fetching posts',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function show(CommunityPost $communityPost)
    {
        try {
            $replies = $communityPost->replies()
                ->with('user:id,name,type,profile_picture')
                ->latest()
                ->paginate(5);

            return $replies;
        } catch (\Exception $e) {
            Log::error('Error fetching replies for post ' . $communityPost->id . ': ' . $e->getMessage() . ' Stack trace: ' . $e->getTraceAsString());
            return response()->json([
                'message' => 'Error fetching replies',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function store(Request $request)
    {
        $request->validate([
            'content' => 'required|string',
            'parent_id' => 'nullable|exists:community_posts,id',
        ]);

        $post = CommunityPost::create([
            'user_id' => Auth::id(),
            'content' => $request->content,
            'parent_id' => $request->parent_id,
        ]);

        $post->load('user:id,name,type,profile_picture');
        
        return $post;
    }
}
