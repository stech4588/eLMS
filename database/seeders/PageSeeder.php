<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Page;

class PageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $pages = [
            ['name' => 'Dashboard', 'slug' => 'dashboard'],
            ['name' => 'Courses', 'slug' => 'coursess'],
            ['name' => 'Privacy Policy', 'slug' => 'privacy.policy'],
            ['name' => 'Terms of Service', 'slug' => 'terms.of.services'],
            ['name' => 'Join Now', 'slug' => 'joinnow'],
            ['name' => 'Become an Instructor', 'slug' => 'BecomeInstructor'],
            ['name' => 'Welcome', 'slug' => 'welcome'],
            ['name' => 'Swiper', 'slug' => 'swiper'],
            ['name' => 'My Career Journey', 'slug' => 'career.journey'],
            ['name' => 'Library', 'slug' => 'library'],
            ['name' => 'Content', 'slug' => 'content'],
            ['name' => 'Add New Courses', 'slug' => 'addnewcourses'],
            ['name' => 'Leadership And Management', 'slug' => 'leadershipAndManagement'],
            ['name' => 'Artificial Intelligence', 'slug' => 'artificialIntelligence'],
            ['name' => 'Cyber Security', 'slug' => 'cyberSecurity'],
            ['name' => 'Instructor', 'slug' => 'Instructor'],
            ['name' => 'Help', 'slug' => 'help'],
            ['name' => 'Cart', 'slug' => 'cart'],
            ['name' => 'Profile', 'slug' => 'profile.edit'],
            ['name' => 'Users', 'slug' => 'users.index'],
            ['name' => 'Invoices', 'slug' => 'invoices.index'],
            ['name' => 'Create Invoice', 'slug' => 'invoices.create'],
            ['name' => 'Course Management', 'slug' => 'course-management.index'],
            ['name' => 'Meta Tags', 'slug' => 'metatags.index'],
            ['name' => 'Instructor Register', 'slug' => 'instructor.register'],
        ];

        foreach ($pages as $page) {
            Page::updateOrCreate(['slug' => $page['slug']], ['name' => $page['name']]);
        }
    }
}
