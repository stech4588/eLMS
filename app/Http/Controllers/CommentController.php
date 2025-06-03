<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\CourseComment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class CommentController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $request->validate([
            'course_id' => 'required|exists:courses,id',
            'body' => 'required|string|max:1000',
        ]);

        CourseComment::create([
            'course_id' => $request->input('course_id'),
            'user_id' => Auth::id(),
            'body' => $request->input('body'),
        ]);

        // Redirect back to the previous page (the course player)
        // Inertia will automatically update props if the controller that renders the page
        // refetches the course with its comments.
        return redirect()->back()->with('success', 'Comment posted successfully.');
    }
} 