<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;

class HelpController extends Controller
{
    public function index()
    {
        $supportTopics = [
            [
                'title' => 'Getting Started',
                'description' => 'This section will guide you through the basics of setting up and using your account.',
                'videos' => [
                    ['title' => 'Login and Modal', 'path' => '/support/Login and modal.mp4', 'roles' => ['student', 'instructor', 'admin']],
                    ['title' => 'Joining', 'path' => '/support/Join.mp4', 'roles' => ['student', 'instructor', 'admin']],
                    ['title' => 'Settings', 'path' => '/support/Settings.mp4', 'roles' => ['student', 'instructor', 'admin']],
                ],
            ],
            [
                'title' => 'Course Management',
                'description' => 'Learn how to create, manage, and view your courses.',
                'videos' => [
                    ['title' => 'Uploading a Course', 'path' => '/support/upload course.mp4', 'roles' => ['instructor', 'admin']],
                    ['title' => 'Managing Courses', 'path' => '/support/course manage.mp4', 'roles' => ['admin']],
                    ['title' => 'Viewing a Course', 'path' => '/support/Course view.mp4', 'roles' => ['student', 'instructor', 'admin']],
                    ['title' => 'Adding a Quiz', 'path' => '/support/Add quiz.mp4', 'roles' => ['instructor', 'admin']],
                    ['title' => 'Adding Takeaways', 'path' => '/support/Add takeaway.mp4', 'roles' => ['instructor', 'admin']],
                ],
            ],
            [
                'title' => 'Community and Groups',
                'description' => 'Discover how to interact with the community and manage your groups.',
                'videos' => [
                    ['title' => 'Community Overview', 'path' => '/support/community.mp4', 'roles' => ['student', 'instructor', 'admin']],
                    ['title' => 'Community Settings', 'path' => '/support/Community settings.mp4', 'roles' => ['admin']],
                    ['title' => 'Group Creation', 'path' => '/support/group creation.mp4', 'roles' => ['instructor', 'admin']],
                    ['title' => 'Groups and Events', 'path' => '/support/Groups and Event.mp4', 'roles' => ['student', 'instructor', 'admin']],
                ],
            ],
            [
                'title' => 'User and Instructor Management',
                'description' => 'Learn how to manage users, invoices, and instructors.',
                'videos' => [
                    ['title' => 'User and Invoice Listing', 'path' => '/support/user and invoice listing.mp4', 'roles' => ['admin']],
                    ['title' => 'Making an Instructor', 'path' => '/support/Make instructor.mp4', 'roles' => ['admin']],
                    ['title' => 'Approving an Instructor', 'path' => '/support/approve instructor.mp4', 'roles' => ['admin']],
                ],
            ],
            [
                'title' => 'Administrative Features',
                'description' => 'An overview of the administrative features available in the portal.',
                'videos' => [
                    ['title' => 'Pricing', 'path' => '/support/pricing.mp4', 'roles' => ['admin']],
                    ['title' => 'Adding Quotes', 'path' => '/support/Add quotes.mp4', 'roles' => ['admin']],
                    ['title' => 'Adding Promotions', 'path' => '/support/Add promotion.mp4', 'roles' => ['admin']],
                    ['title' => 'Adding Prompts', 'path' => '/support/Add prompt.mp4', 'roles' => ['admin']],
                    ['title' => 'Job Posting', 'path' => '/support/job posting.mp4', 'roles' => ['instructor', 'admin']],
                    ['title' => 'AI Assistance', 'path' => '/support/Ai assistance.mp4', 'roles' => ['student', 'instructor', 'admin']],
                ],
            ],
        ];

        return Inertia::render('help/help', [
            'supportTopics' => $supportTopics,
        ]);
    }
}
