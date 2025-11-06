<?php

namespace App\Http\Controllers;

use App\Models\CommunityPost;
use App\Models\CommunityPostAttachment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Events\CommunityPostCreated;

class CommunityPostController extends Controller
{
    public function index(Request $request)
    {
        try {
            $posts = CommunityPost::with(['user:id,name,type,profile_picture', 'attachments'])
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
                ->with(['user:id,name,type,profile_picture', 'attachments'])
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
            'attachments.*' => 'file|mimes:jpeg,png,jpg,gif,svg,pdf,doc,docx,zip,txt|max:10240',
        ]);

        $post = CommunityPost::create([
            'user_id' => Auth::id(),
            'content' => $request->content,
            'parent_id' => $request->parent_id,
        ]);

        if ($request->hasFile('attachments')) {
            foreach ($request->file('attachments') as $file) {
                $originalFileName = $file->getClientOriginalName();
                $fileType = $file->getClientMimeType();
                $fileNameToStore = time() . '_' . $originalFileName;

                $path = 'community/files';
                if (strpos($fileType, 'image') === 0) {
                    $path = 'community/images';
                }
                
                $filePath = $file->move(public_path($path), $fileNameToStore);

                $post->attachments()->create([
                    'file_path' => $path . '/' . $fileNameToStore,
                    'file_name' => $originalFileName,
                    'file_type' => strpos($fileType, 'image') === 0 ? 'image' : 'file',
                ]);
            }
        }

        $post->load(['user:id,name,type,profile_picture', 'attachments']);

        // Broadcast new post to other community members
        broadcast(new CommunityPostCreated($post))->toOthers();

        return $post;
    }
}
