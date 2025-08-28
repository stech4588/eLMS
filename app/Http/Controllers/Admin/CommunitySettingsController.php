<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Inertia\Inertia;

class CommunitySettingsController extends Controller
{
    public function index()
    {
        $users = User::whereIn('type', ['student', 'instructor'])
            ->select('id', 'name', 'email', 'type', 'can_view_community')
            ->orderBy('name')
            ->paginate(20);

        return Inertia::render('Admin/CommunitySettings', [
            'users' => $users
        ]);
    }

    public function toggleAccess(Request $request, User $user)
    {
        $request->validate([
            'can_view_community' => 'required|boolean',
        ]);

        $user->update([
            'can_view_community' => $request->can_view_community,
        ]);

        return back();
    }
}
