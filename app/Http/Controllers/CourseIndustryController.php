<?php

namespace App\Http\Controllers;

use App\Models\CourseIndustry;
use Illuminate\Http\Request;
use Inertia\Inertia;

class CourseIndustryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Inertia::render('CourseManagement/Index', [
            'courseIndustries' => CourseIndustry::paginate(10),
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
        CourseIndustry::create($request->all());
        return redirect()->back();
    }

    /**
     * Display the specified resource.
     */
    public function show(CourseIndustry $courseIndustry)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(CourseIndustry $courseIndustry)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, CourseIndustry $courseIndustry)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);
        $courseIndustry->update($request->all());
        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(CourseIndustry $courseIndustry)
    {
        $courseIndustry->delete();
        return redirect()->back();
    }

  
}
