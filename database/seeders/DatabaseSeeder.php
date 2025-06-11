<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();
         $this->call(PermissionSeeder::class);
        $this->call(RoleSeeder::class);
        $this->call(CourseCertificateSeeder::class);
        $this->call(CourseTopicSeeder::class);
        $this->call(CourseIndustrySeeder::class);
        $this->call(CourseTypeSeeder::class);
        
        User::updateOrCreate(
            [
                'email' => 'test@example.com',
                'name' => 'Test User',
                'role_id' => 1, // Assuming role_id 1 is for 'Super Admin'
            ],
        );
    }
}
