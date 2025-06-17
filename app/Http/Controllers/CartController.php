<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Course;

class CartController extends Controller
{
    public function index(Request $request)
    {
        $courseId = $request->query('course_id');
        $course = Course::with(['videos', 'courseType', 'industry', 'certificate'])->find($courseId);

        if (!$course) {
            // Handle course not found, maybe redirect back with an error
            return redirect()->back()->with('error', 'Course not found.');
        }

        return Inertia::render('cart/cart', [
            'course' => $course,
        ]);
    }
}
