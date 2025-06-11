<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\CourseType;

class CourseTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        CourseType::create(['name' => 'Course']);
        CourseType::create(['name' => 'Specialization']);
        CourseType::create(['name' => 'Professional Certificate']);
        CourseType::create(['name' => 'MasterTrack Certificate']);
    }
}
