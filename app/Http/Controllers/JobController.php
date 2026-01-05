<?php

namespace App\Http\Controllers;

use App\Models\Job;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class JobController extends Controller
{
    public function index(Request $request)
    {
        $jobs = Job::with('user')
            ->when($request->input('search'), function ($query, $search) {
                $query->where('title', 'like', "%{$search}%")
                    ->orWhere('skills', 'like', "%{$search}%");
            })
            ->when($request->input('skill'), function ($query, $skill) {
                $query->where('skills', 'like', "%{$skill}%");
            })
            ->when($request->input('date'), function ($query, $date) {
                $query->whereDate('created_at', $date);
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        $skills = Job::pluck('skills')->flatMap(function ($skillString) {
            return explode(',', $skillString);
        })->map(function ($skill) {
            return trim($skill);
        })->unique()->values()->all();

        return Inertia::render('Jobs/Index', [
            'jobs' => $jobs,
            'filters' => $request->only(['search', 'skill', 'date']),
            'skills' => $skills,
            'authUserId' => Auth::id(),
        ]);
    }

    public function create()
    {
        return Inertia::render('Jobs/Create');
    }

    public function store(Request $request)
    {
        $user = Auth::user();

        $rules = [
            'title' => 'required|string|max:255',
            'skills' => 'required|string',
            'description' => 'required|string',
            'apply_url' => 'required|url|max:2048',
            'contact_phone' => 'nullable|string|max:255',
            'contact_email' => 'nullable|email|max:255',
        ];

        if (!$user->phone_number) {
            $rules['contact_phone'] = 'required|string|max:255';
        }

        if (!$user->email) {
            $rules['contact_email'] = 'required|email|max:255';
        }
        
        $validatedData = $request->validate($rules);

        Job::create([
            'user_id' => $user->id,
            'title' => $validatedData['title'],
            'skills' => $validatedData['skills'],
            'description' => $validatedData['description'],
            'apply_url' => $validatedData['apply_url'],
            'contact_phone' => $validatedData['contact_phone'] ?? $user->phone_number,
            'contact_email' => $validatedData['contact_email'] ?? $user->email,
        ]);

        return redirect('/jobs')->with('success', 'Job posted successfully.');
    }

    public function update(Request $request, Job $job)
    {
        $user = Auth::user();
        if ($job->user_id !== $user->id) {
            abort(403);
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'skills' => 'required|string',
            'description' => 'required|string',
            'apply_url' => 'required|url|max:2048',
            'contact_phone' => 'nullable|string|max:255',
            'contact_email' => 'nullable|email|max:255',
        ]);

        $job->update($validated);

        return redirect('/jobs')->with('success', 'Job updated successfully.');
    }

    public function destroy(Job $job)
    {
        $user = Auth::user();
        if ($job->user_id !== $user->id) {
            abort(403);
        }

        $job->delete();

        return redirect('/jobs')->with('success', 'Job deleted successfully.');
    }
}
