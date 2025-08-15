<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Pricing;
class PricingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $monthlyPlans = [
            [
                'plan_name' => 'Earn',
                'slug' => 'earn-monthly',
                'description' => 'Choose 1 Business Model, Daily Live Broadcasts, Real-Time Course Updates, 3 Connected Devices, Community Access',
                'price' => 49,
                'type' => 'monthly'
            ],
            [
                'plan_name' => 'Prosper',
                'slug' => 'prosper-monthly',
                'description' => 'Everything in Earn, 1 Extra Business Model, Priority Support, 5 Connected Devices, Exclusive Workshops',
                'price' => 69,
                'type' => 'monthly'
            ],
            [
                'plan_name' => 'Conquer',
                'slug' => 'conquer-monthly',
                'description' => 'Everything in Prosper, 9+ Extra Business Models, VIP Community Access, 7 Connected Devices, Early Access to New Content',
                'price' => 99,
                'type' => 'monthly'
            ]
        ];

        $yearlyPlans = [
            [
                'plan_name' => 'Earn',
                'slug' => 'earn-yearly',
                'description' => 'Choose 1 Business Model, Daily Live Broadcasts, Real-Time Course Updates, 3 Connected Devices, Community Access',
                'price' => 492,
                'type' => 'yearly'
            ],
            [
                'plan_name' => 'Prosper',
                'slug' => 'prosper-yearly',
                'description' => 'Everything in Earn, 1 Extra Business Model, Priority Support, 5 Connected Devices, Exclusive Workshops',
                'price' => 699,
                'type' => 'yearly'
            ],
            [
                'plan_name' => 'Conquer',
                'slug' => 'conquer-yearly',
                'description' => 'Everything in Prosper, 9+ Extra Business Models, VIP Community Access, 7 Connected Devices, Early Access to New Content',
                'price' => 999,
                'type' => 'yearly'
            ]
        ];

        foreach ($monthlyPlans as $plan) {
            Pricing::updateOrCreate($plan);
        }

        foreach ($yearlyPlans as $plan) {
            Pricing::updateOrCreate($plan);
        }
    }
}
