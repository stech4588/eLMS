<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\CourseIndustry;

class CourseIndustrySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        CourseIndustry::create(['name' => 'IT and Software']);
        CourseIndustry::create(['name' => 'Healthcare']);
        CourseIndustry::create(['name' => 'Finance and Banking']);
        CourseIndustry::create(['name' => 'Education']);
        CourseIndustry::create(['name' => 'Manufacturing']);
    }
}
