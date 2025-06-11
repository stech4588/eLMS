<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\CourseTopic;

class CourseTopicSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        CourseTopic::create(['name' => 'Data Science']);
        CourseTopic::create(['name' => 'Business']);
        CourseTopic::create(['name' => 'Computer Science']);
        CourseTopic::create(['name' => 'Information Technology']);
        CourseTopic::create(['name' => 'Language Learning']);
        CourseTopic::create(['name' => 'Health']);
        CourseTopic::create(['name' => 'Personal Development']);
        CourseTopic::create(['name' => 'Arts and Humanities']);
    }
}
