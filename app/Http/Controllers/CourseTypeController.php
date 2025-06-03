<?php

namespace App\Http\Controllers;

use App\Models\CourseCertificate;
use App\Models\CourseType;
use App\Models\Topic;
use App\Models\CourseIndustry;
use Illuminate\Http\Request;
use Inertia\Inertia;

class CourseTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Inertia::render('CourseManagement/Index', [
            'courseTypes' => CourseType::paginate(10),
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
        $request->validate([
            'name' => 'required|string|max:255',
        ]);
        CourseType::create($request->all());
        return redirect()->back();
    }

    /**
     * Display the specified resource.
     */
    public function show(CourseType $courseType)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(CourseType $courseType)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, CourseType $courseType)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);
        $courseType->update($request->all());
        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(CourseType $courseType)
    {
        $courseType->delete();
        return redirect()->back();
    }

    public function showManagementPage()
    {
        return Inertia::render('CourseManagement/Index', [
            'courseTypes' => CourseType::paginate(10),
            'topics' => Topic::paginate(10),
            'courseCertificates' => CourseCertificate::paginate(10),
            'courseIndustries' => CourseIndustry::paginate(10),
        ]);
    }
}
