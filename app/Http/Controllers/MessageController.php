<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Group;
use App\Models\Message;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use App\Events\MessageSent;

class MessageController extends Controller
{
    public function index(Group $group)
    {
        // Authorize that the user is a member of the group
        if (!$group->members->contains(Auth::id())) {
            abort(403);
        }

        return $group->messages()->with('user:id,name,profile_picture', 'groupEvent')->latest()->paginate(50)
            ->through(fn ($message) => [
                'id' => $message->id,
                'content' => $message->content,
                'file_path' => $message->file_path,
                'file_type' => $message->file_type,
                'created_at' => $message->created_at,
                'user' => [
                    'id' => $message->user->id,
                    'name' => $message->user->name,
                    'profile_photo_url' => $message->user->profile_photo_url,
                ],
                'group_event' => $message->groupEvent,
            ]);
    }

    public function store(Request $request, Group $group)
    {
        // Authorize that the user is a member of the group
        if (!$group->members->contains(Auth::id())) {
            abort(403);
        }

        $validator = validator($request->all(), [
            'content' => 'nullable|string|required_without:attachment',
            'attachment' => 'nullable|file|mimes:jpg,jpeg,png,gif,mp4,mov,avi,pdf,doc,docx,xls,xlsx|max:20480|required_without:content', // 20MB Max
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $filePath = null;
        $fileType = null;

        if ($request->hasFile('attachment')) {
            try {
                $file = $request->file('attachment');
                $fileName = time().'_'.$file->getClientOriginalName();
                $destinationPath = public_path('group_attachments');
                $fileType = $this->getFileType($file->getMimeType()); // Get MIME type before moving
                
                // Ensure the directory exists
                if (!file_exists($destinationPath)) {
                    mkdir($destinationPath, 0755, true);
                }

                $file->move($destinationPath, $fileName);
                $filePath = 'group_attachments/' . $fileName;
            } catch (\Exception $e) {
                return response()->json(['error' => 'Failed to store attachment.'], 500);
            }
        }

        if (!$request->input('content') && !$filePath) {
            return response()->json(['error' => 'A message or an attachment is required.'], 422);
        }

        $message = $group->messages()->create([
            'user_id' => Auth::id(),
            'content' => $request->input('content'),
            'file_path' => $filePath,
            'file_type' => $fileType,
        ]);

        $message->load('user:id,name,profile_picture');

		// Broadcast the message to other group members (avoid echoing to sender)
		broadcast(new MessageSent($message))->toOthers();

        return [
            'id' => $message->id,
            'content' => $message->content,
            'file_path' => $message->file_path,
            'file_type' => $message->file_type,
            'created_at' => $message->created_at,
            'user' => [
                'id' => $message->user->id,
                'name' => $message->user->name,
                'profile_photo_url' => $message->user->profile_photo_url,
            ],
            'group_event' => null, // Regular messages won't have an event
        ];
    }

    private function getFileType($mimeType)
    {
        if (Str::startsWith($mimeType, 'image/')) {
            return 'image';
        }
        if (Str::startsWith($mimeType, 'video/')) {
            return 'video';
        }
        return 'file';
    }
}
