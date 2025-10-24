<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Group;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use App\Models\GroupInvitation;
use App\Mail\GroupInvitationMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class GroupController extends Controller
{
    public function index(Request $request)
    {
        $query = Group::query();

        if ($request->has('search')) {
            $query->where('name', 'like', '%' . $request->input('search') . '%');
        }

        $groups = $query->with('creator', 'members')->latest()->paginate(10);

        if (Auth::check()) {
            $groups->getCollection()->transform(function ($group) {
                $group->is_member = $group->members->contains(Auth::id());
                return $group;
            });
        }

        return Inertia::render('Groups/Index', [
            'groups' => $groups,
            'filters' => $request->only('search'),
        ]);
    }

    public function join(Group $group)
    {
        if (!$group->members->contains(Auth::id())) {
            $group->members()->attach(Auth::id(), ['role' => 'member']);
            return back()->with('success', 'You have joined the group.');
        }

        return back()->with('info', 'You are already a member of this group.');
    }

    public function chat(Group $group)
    {
        if (!$group->members->contains(Auth::id())) {
            abort(403, 'You are not a member of this group.');
        }

        $group->load('creator', 'members');

        return Inertia::render('Groups/Chat', [
            'group' => $group,
        ]);
    }

    public function invite(Request $request, Group $group)
    {
        Log::info('Invite function started.');
        $request->validate(['email' => 'required|email']);
        Log::info('Validation passed.');

        // Check if the authenticated user is an admin of the group
        $isAdmin = $group->groupMembers()->where('user_id', Auth::id())->where('role', 'admin')->exists();
        if (!$isAdmin) {
            Log::warning('Permission denied: User ' . Auth::id() . ' is not an admin of group ' . $group->id);
            return back()->with('error', 'You do not have permission to invite members to this group.');
        }
        Log::info('Admin check passed.');

        // Check if the user is already a member
        $existingMember = $group->members()->where('email', $request->email)->exists();

        if ($existingMember) {
            Log::info('User with email ' . $request->email . ' is already a member.');
            return back()->with('info', 'This user is already a member of the group.');
        }
        Log::info('Existing member check passed.');
        
        // Create a unique token
        $token = Str::random(60);
        Log::info('Token created: ' . $token);

        GroupInvitation::create([
            'group_id' => $group->id,
            'email' => $request->email,
            'token' => $token,
        ]);
        Log::info('GroupInvitation record created in database.');
        
        Log::info("Attempting to send invitation email to {$request->email} for group {$group->name}.");

        try {
            $mailable = new GroupInvitationMail($group, Auth::user()->name, $token);
            Log::info("Mailable created. Rendering view...");
            $html = $mailable->render();
            Log::info("Email rendered successfully. HTML length: " . strlen($html));
            
            Mail::to($request->email)->send($mailable);
            Log::info("Invitation email to {$request->email} sent successfully.");
        } catch (\Exception $e) {
            Log::error("Failed to send invitation email to {$request->email}. Error: " . $e->getMessage(), ['exception' => $e]);
        }

        Log::info('Redirecting back with success message.');
        return back()->with('success', 'Invitation sent successfully.');
    }

    public function acceptInvite($token)
    {
        $invitation = GroupInvitation::where('token', $token)->where('status', 'pending')->firstOrFail();

        // Check if the user is logged in
        if (!Auth::check()) {
            return redirect()->route('login', ['redirect' => route('groups.acceptInvite', $token)]);
        }

        // Add the user to the group
        $invitation->group->members()->attach(Auth::id(), ['role' => 'member']);

        // Update the invitation status
        $invitation->update(['status' => 'accepted']);

        return redirect()->route('groups.index')->with('success', 'You have successfully joined the group: ' . $invitation->group->name);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'profile_picture' => 'nullable|image|mimes:jpg,jpeg,png|max:2048', // 2MB Max
        ]);

        $filePath = null;
        if ($request->hasFile('profile_picture')) {
            $file = $request->file('profile_picture');
            $fileName = time().'_'.$file->getClientOriginalName();
            $file->move(public_path('group_profile_pictures'), $fileName);
            $filePath = 'group_profile_pictures/' . $fileName;
        }

        $group = Group::create([
            'name' => $request->name,
            'description' => $request->description,
            'creator_id' => Auth::id(),
            'profile_picture' => $filePath,
        ]);

        $group->members()->attach(Auth::id(), ['role' => 'admin']);

        return redirect()->route('groups.index')->with('success', 'Group created successfully.');
    }

    public function updateNotificationSettings(Request $request, Group $group)
    {
        $request->validate([
            'receive_email_notifications' => 'required|boolean',
        ]);

        $member = $group->members()->where('user_id', Auth::id())->first();

        if ($member) {
            $group->members()->updateExistingPivot(Auth::id(), [
                'receive_email_notifications' => $request->receive_email_notifications,
            ]);
            return response()->json(['message' => 'Notification settings updated.']);
        }

        return response()->json(['message' => 'You are not a member of this group.'], 403);
    }
}
