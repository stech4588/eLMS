<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class LeaderboardController extends Controller
{
    public function getTopUsers()
    {
        $topUsers = User::orderBy('points', 'desc')->take(5)->get();

        $badges = [
            1 => ['name' => 'Top Learner', 'icon_url' => '/images/badge1.svg'],
            2 => ['name' => 'Fast Learner', 'icon_url' => '/images/badge2.svg'],
            3 => ['name' => 'Subject Master', 'icon_url' => '/images/badge3.svg'],
            'engagement' => ['name' => 'Rising Star', 'icon_url' => '/images/badge4.svg'],
        ];

        $topUsers->each(function ($user, $index) use ($badges) {
            $rank = $index + 1;
            $userBadges = [];

            if ($rank === 1) {
                $userBadges[] = $badges[1];
            } elseif ($rank === 2) {
                $userBadges[] = $badges[2];
            } elseif ($rank === 3) {
                $userBadges[] = $badges[3];
            }

            if ($user->points > 0) {
                $userBadges[] = $badges['engagement'];
            }
            
            $user->badges = $userBadges;
        });

        return response()->json($topUsers);
    }
}
