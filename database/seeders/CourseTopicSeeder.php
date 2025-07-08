<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Topic;
use Illuminate\Support\Facades\DB;

class CourseTopicSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Disable foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        // Clear the table before seeding
        DB::table('topics')->truncate();

        // Re-enable foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $topics = [
            ['name' => 'E-Commerce', 'logo_url' => '/images/topicsLogo/e-commerce-logo.png'],
            ['name' => 'Copywriting', 'logo_url' => '/images/topicsLogo/copywriting-logo.png'],
            ['name' => 'Stocks', 'logo_url' => '/images/topicsLogo/stocks-logo.png'],
            ['name' => 'Crypto Investing', 'logo_url' => '/images/topicsLogo/crypto-logo.png'],
            ['name' => 'Business & Finance', 'logo_url' => '/images/topicsLogo/finance-logo.png'],
            ['name' => 'Crypto Trading', 'logo_url' => '/images/topicsLogo/trading-logo.png'],
            ['name' => 'Content Creation & Al', 'logo_url' => '/images/topicsLogo/AI-logo.png'],
            ['name' => 'Client Acquisition & Social Media', 'logo_url' => '/images/topicsLogo/client-logo.png'],
            ['name' => 'ADS Mastery', 'logo_url' => '/images/topicsLogo/ads-logo.png'],
            ['name' => 'DeFi', 'logo_url' => '/images/topicsLogo/defi-logo.png'],
            ['name' => 'Digital Advertising', 'logo_url' => '/images/topicsLogo/sdvertising-logo.png'],
            ['name' => 'Sales', 'logo_url' => '/images/topicsLogo/sales-logo.png'],
            ['name' => 'Airbnb', 'logo_url' => '/images/topicsLogo/airbnb-logo.png'],
            ['name' => 'Influencer Network Management', 'logo_url' => '/images/topicsLogo/network-logo.png'],
            ['name' => 'Digital Marketing', 'logo_url' => '/images/topicsLogo/marketing-logo.png'],
            ['name' => 'Lead Generation', 'logo_url' => '/images/topicsLogo/lead-logo.png'],
            ['name' => 'Credit Repair', 'logo_url' => '/images/topicsLogo/credit-logo.png'],
            ['name' => 'Drop Shipping', 'logo_url' => '/images/topicsLogo/drop-logo.png'],
            ['name' => 'Social Media Automation', 'logo_url' => '/images/topicsLogo/social-logo.png'],
            ['name' => 'SEO Consulting', 'logo_url' => '/images/topicsLogo/seo-logo.png'],
        ];

        foreach ($topics as $topic) {
            Topic::create($topic);
        }
    }
}
