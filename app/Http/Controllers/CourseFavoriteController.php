<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\CourseFavorite;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CourseFavoriteController extends Controller
{
    /**
     * Toggle the favorite status of a course for the authenticated user.
     *
     * @param  \App\Models\Course  $course
     * @return \Illuminate\Http\RedirectResponse
     */
    public function toggle(Course $course)
    {
        $user = Auth::user();

        if (!$user) {
            return redirect()->back()->with('error', 'You must be logged in to favorite courses.');
        }

        DB::beginTransaction();
        try {
            $favorite = CourseFavorite::where('user_id', $user->id)
                                      ->where('course_id', $course->id)
                                      ->first();

            if ($favorite) {
                $favorite->delete();
                DB::commit();
                return redirect()->back()->with('status', 'Course removed from favorites.');
            } else {
                CourseFavorite::create([
                    'user_id' => $user->id,
                    'course_id' => $course->id,
                ]);
                DB::commit();
                return redirect()->back()->with('status', 'Course added to favorites.');
            }
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error toggling favorite for course ID ' . $course->id . ' by user ID ' . $user->id . ': ' . $e->getMessage());
            return redirect()->back()->with('error', 'Could not update favorites. Please check logs. Message: ' . $e->getMessage());
        }
    }
}
