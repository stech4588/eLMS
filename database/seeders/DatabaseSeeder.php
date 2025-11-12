<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

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
        $this->call(PageSeeder::class);
        $this->call(PricingSeeder::class);
       
        User::updateOrCreate(
            [
                'email' => 'test@example.com'
            ],
            [
                'name' => 'Test User',
                'password' => Hash::make('password'),
                'role_id' => 1, // Assuming role_id 1 is for 'Super Admin'
                'type' => 'admin',
                'is_active' => 1,
                'phone_number' => '1234567890',
                'profile_picture' => 'https://via.placeholder.com/150',
                'primary_learning_goal' => 'AI',
                'preferred_topic_ids' => ["1","5","9"],
                'resume_path' => 'https://via.placeholder.com/150',
                
            ]
        );
    }
}
