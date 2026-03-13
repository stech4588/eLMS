<?php

namespace App\Http\Controllers;

use App\Models\CommunityPost;
use App\Models\CommunityPostAttachment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Events\CommunityPostCreated;
use App\Models\CommunityPostLike;
use App\Models\CommunityPostPoll;
use App\Models\CommunityPostPollOption;
use App\Models\CommunityPostPollVote;
use Carbon\Carbon;

class CommunityPostController extends Controller
{
    public function index(Request $request)
    {
        try {
            $posts = CommunityPost::with(['user:id,name,type,profile_picture', 'attachments', 'poll.options' => function($query) {
                    $query->withCount('votes');
                }])
                ->whereNull('parent_id')
                ->when($request->category, function ($query, $category) {
                    if ($category !== 'all') {
                        return $query->where('category', $category);
                    }
                })
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
            // Load the main post with likes information
            $communityPost->loadCount('likes');
            $communityPost->append('is_liked');
            $communityPost->load(['poll.options' => function($query) {
                $query->withCount('votes');
            }]);

            $replies = $communityPost->replies()
                ->with(['user:id,name,type,profile_picture', 'attachments'])
                ->withCount(['likes', 'replies']) // Add likes and replies count
                ->latest()
                ->paginate(10);

            // Append is_liked attribute for each reply
            $replies->getCollection()->transform(function ($reply) {
                $reply->append('is_liked');
                return $reply;
            });

            return response()->json([
                'post' => $communityPost,
                'replies' => $replies,
            ]);
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
            'title' => 'required_without:parent_id|string|max:255',
            'content' => 'required|string',
            'parent_id' => 'nullable|exists:community_posts,id',
            'attachments.*' => 'file|mimes:jpeg,png,jpg,gif,svg,pdf,doc,docx,zip,txt|max:10240',
            'links' => 'nullable|array',
            'links.*' => 'required|url',
        ]);

        $post = CommunityPost::create([
            'user_id' => Auth::id(),
            'title' => $request->title,
            'content' => $request->content,
            'category' => $request->category ?? 'general',
            'parent_id' => $request->parent_id,
            'links' => $request->links,
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

        if ($request->poll_options && is_array($request->poll_options)) {
            $poll = $post->poll()->create([
                'question' => $request->poll_question,
                'multiple_choice' => $request->poll_multiple_choice ?? false,
                'ends_at' => $request->poll_ends_at ? Carbon::parse($request->poll_ends_at) : null,
            ]);

            foreach ($request->poll_options as $optionText) {
                if (!empty($optionText)) {
                    $poll->options()->create(['option_text' => $optionText]);
                }
            }
        }

        $post->load(['user:id,name,type,profile_picture', 'attachments', 'poll.options' => function($query) {
            $query->withCount('votes');
        }]);
        $post->loadCount('likes');
        $post->append('is_liked');

        // Broadcast new post to other community members
        broadcast(new CommunityPostCreated($post))->toOthers();

        return $post;
    }

    public function destroy(CommunityPost $communityPost)
    {
        if ($communityPost->user_id !== Auth::id()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        try {
            $communityPost->delete();
            return response()->json(['message' => 'Post trashed successfully']);
        } catch (\Exception $e) {
            Log::error('Error soft deleting community post: ' . $e->getMessage());
            return response()->json(['message' => 'Error deleting post'], 500);
        }
    }

    public function update(Request $request, CommunityPost $communityPost)
    {
        if ($communityPost->user_id !== Auth::id()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $request->validate([
            'title' => 'required_without:parent_id|string|max:255',
            'content' => 'required|string',
            'attachments.*' => 'file|mimes:jpeg,png,jpg,gif,svg,pdf,doc,docx,zip,txt|max:10240',
            'links' => 'nullable|array',
            'links.*' => 'required|url',
        ]);

        try {
            $communityPost->update([
                'title' => $request->title,
                'content' => $request->content,
                'category' => $request->category ?? $communityPost->category,
                'links' => $request->has('links') ? (is_array($request->links) ? $request->links : []) : $communityPost->links,
            ]);

            // Handle removed attachments
            if ($request->has('existing_attachments')) {
                $keepingIds = is_array($request->existing_attachments) ? $request->existing_attachments : [];
                $toRemove = $communityPost->attachments()->whereNotIn('id', $keepingIds)->get();
                foreach($toRemove as $att) {
                    $fullPath = public_path($att->file_path);
                    if (file_exists($fullPath)) unlink($fullPath);
                    $att->delete();
                }
            } elseif ($request->has('title')) { // Only if updating main post fields
                // If it's an update and existing_attachments is missing but was provided as empty or null, 
                // it might mean user removed everything if they are on a modern UI.
                // However, safety first: check if user specifically wants to clear all.
                if ($request->existing_attachments === '') {
                     $toRemove = $communityPost->attachments()->get();
                     foreach($toRemove as $att) {
                        $fullPath = public_path($att->file_path);
                        if (file_exists($fullPath)) unlink($fullPath);
                        $att->delete();
                    }
                }
            }

            if ($request->hasFile('attachments')) {
                foreach ($request->file('attachments') as $file) {
                    $originalFileName = $file->getClientOriginalName();
                    $fileType = $file->getClientMimeType();
                    $fileNameToStore = time() . '_' . $originalFileName;

                    $path = 'community/files';
                    if (strpos($fileType, 'image') === 0) {
                        $path = 'community/images';
                    }
                    
                    $file->move(public_path($path), $fileNameToStore);

                    $communityPost->attachments()->create([
                        'file_path' => $path . '/' . $fileNameToStore,
                        'file_name' => $originalFileName,
                        'file_type' => (strpos($fileType, 'image') === 0) ? 'image' : 'file',
                        'user_id' => Auth::id() // assuming attachments has user_id
                    ]);
                }
            }

            if ($request->remove_poll) {
                $communityPost->poll()->delete();
            } elseif ($request->poll_options && is_array($request->poll_options)) {
                $poll = $communityPost->poll ?: new CommunityPostPoll(['community_post_id' => $communityPost->id]);
                $poll->fill([
                    'question' => $request->poll_question,
                    'multiple_choice' => $request->poll_multiple_choice ?? false,
                    'ends_at' => $request->poll_ends_at ? Carbon::parse($request->poll_ends_at) : null,
                ])->save();

                $existingOptions = $poll->options()->orderBy('id')->get();
                $newOptionTexts = array_filter($request->poll_options, fn($val) => !empty($val));

                if ($existingOptions->count() !== count($newOptionTexts)) {
                    $poll->options()->delete();
                    foreach ($newOptionTexts as $optionText) {
                        $poll->options()->create(['option_text' => $optionText]);
                    }
                } else {
                    foreach ($newOptionTexts as $index => $optionText) {
                        $existingOptions[$index]->update(['option_text' => $optionText]);
                    }
                }
            }

            $communityPost->load(['user:id,name,type,profile_picture', 'attachments', 'poll.options' => function($query) {
                $query->withCount('votes');
            }]);
            $communityPost->loadCount('likes');
            $communityPost->append('is_liked');
            
            return $communityPost;
        } catch (\Exception $e) {
            Log::error('Error updating community post: ' . $e->getMessage());
            return response()->json(['message' => 'Error updating post'], 500);
        }
    }

    public function toggleLike(CommunityPost $communityPost)
    {
        $like = $communityPost->likes()->where('user_id', Auth::id())->first();

        if ($like) {
            $like->delete();
            return response()->json(['liked' => false, 'likes_count' => $communityPost->likes()->count()]);
        }

        $communityPost->likes()->create(['user_id' => Auth::id()]);
        return response()->json(['liked' => true, 'likes_count' => $communityPost->likes()->count()]);
    }

    public function restore($id)
    {
        try {
            $post = CommunityPost::withTrashed()->findOrFail($id);
            
            if ($post->user_id !== Auth::id()) {
                return response()->json(['message' => 'Unauthorized'], 403);
            }

            $post->restore();
            return response()->json(['message' => 'Post restored successfully']);
        } catch (\Exception $e) {
            Log::error('Error restoring community post: ' . $e->getMessage());
            return response()->json(['message' => 'Error restoring post'], 500);
        }
    }
    public function vote(Request $request, CommunityPostPoll $poll)
    {
        $request->validate([
            'option_id' => 'required|exists:community_post_poll_options,id',
        ]);

        $userId = Auth::id();

        // Check if poll has ended
        if ($poll->ends_at && $poll->ends_at->isPast()) {
            return response()->json(['message' => 'This poll has ended.'], 422);
        }

        if (!$poll->multiple_choice) {
            // Remove existing votes for this poll by this user
            CommunityPostPollVote::where('user_id', $userId)
                ->where('community_post_poll_id', $poll->id)
                ->delete();
        } else {
            // Toggle vote for multiple choice
            $existing = CommunityPostPollVote::where('user_id', $userId)
                ->where('community_post_poll_id', $poll->id)
                ->where('community_post_poll_option_id', $request->option_id)
                ->first();
            
            if ($existing) {
                $existing->delete();
                $poll->load(['options' => function($query) {
                    $query->withCount('votes');
                }]);
                return response()->json([
                    'message' => 'Vote removed',
                    'poll' => $poll->append(['user_voted_option_ids', 'total_votes'])
                ]);
            }
        }

        CommunityPostPollVote::create([
            'user_id' => $userId,
            'community_post_poll_id' => $poll->id,
            'community_post_poll_option_id' => $request->option_id,
        ]);

        $poll->load(['options' => function($query) {
            $query->withCount('votes');
        }]);

        return response()->json([
            'message' => 'Vote recorded',
            'poll' => $poll->append(['user_voted_option_ids', 'total_votes'])
        ]);
    }
}
