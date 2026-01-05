<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Http\RedirectResponse;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): Response
    {
        return Inertia::render('userListing/userlist', [
            'users' => User::all()
        ]);
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
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user): Response
    {
        return Inertia::render('userListing/EditUser', [
            'user' => $user
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user): RedirectResponse
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
        ]);

        $user->update($validatedData);

        return redirect('/users')->with('message', 'User updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        $user->delete();
        return redirect('/users')->with('success', 'User deleted successfully.');
    }

    public function updatePhoneNumber(Request $request)
    {
        $validated = $request->validate([
            'phone_number' => 'required|string|max:20',
            'phone_country_code' => 'nullable|string|max:10',
        ]);

        $user = $request->user();
        $user->phone_number = $validated['phone_number'];
        // Only update phone_country_code if the column exists in the database
        // For now, we'll just save phone_number as it's the main field
        $user->save();

        if ($request->expectsJson()) {
            return response()->json(['success' => true, 'message' => 'Phone number updated successfully.']);
        }

        return redirect()->back()->with('message', 'Phone number updated successfully.');
    }

    public function updateCareerGoal(Request $request)
    {
        $validated = $request->validate([
            'primary_learning_goal' => 'required|string|max:255',
        ]);

        $user = $request->user();
        $user->primary_learning_goal = $validated['primary_learning_goal'];
        $user->save();

        if ($request->expectsJson()) {
            return response()->json(['success' => true, 'message' => 'Career goal updated successfully.']);
        }

        return redirect()->back()->with('message', 'Career goal updated successfully.');
    }

    public function updatePreferredTopics(Request $request)
    {
        $validated = $request->validate([
            'preferred_topic_ids' => 'sometimes|array',
            'preferred_topic_ids.*' => 'exists:topics,id',
        ]);

        $user = $request->user();
        $user->preferred_topic_ids = $validated['preferred_topic_ids'] ?? [];
        $user->save();

        if ($request->expectsJson()) {
            return response()->json(['success' => true, 'message' => 'Preferred topics updated successfully.']);
        }

        return redirect()->back()->with('message', 'Preferred topics updated successfully.');
    }

    public function storeLearningGoal(Request $request)
    {
        $request->validate([
            'daily_learning_goal' => 'required|integer|min:1|max:4',
        ]);

        $request->user()->update([
            'daily_learning_goal' => $request->daily_learning_goal,
        ]);

        return redirect()->back()->with('success', 'Your learning goal has been saved!');
    }
} 