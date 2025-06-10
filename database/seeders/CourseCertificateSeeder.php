<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\CourseCertificate;

class CourseCertificateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        CourseCertificate::create(['name' => 'Certificate of Completion']);
        CourseCertificate::create(['name' => 'Professional Certificate']);
        CourseCertificate::create(['name' => 'Coursera Certificate']);
    }
}
