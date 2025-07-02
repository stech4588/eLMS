<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;
use App\Models\Topic;

class CareerJourneyController extends Controller
{
    /**
     * Display the career journey page.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Inertia\Response
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        
        $preferredTopics = collect();
        if ($user && !empty($user->preferred_topic_ids)) {
            $topicIds = $user->preferred_topic_ids;
            if (is_string($topicIds)) {
                $topicIds = json_decode($topicIds, true);
            }
            $preferredTopics = Topic::whereIn('id', $topicIds)->get();
        }

        $allTopics = Topic::all();

        return Inertia::render('careerJourney/myCareerJourney', [
            'auth' => [
                'user' => $user
            ],
            'preferredTopics' => $preferredTopics,
            'allTopics' => $allTopics,
        ]);
    }
} 