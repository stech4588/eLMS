<?php

return [
    'default_amount' => (float) env('SUBSCRIPTION_DEFAULT_AMOUNT', 49.00),
    'default_plan' => env('SUBSCRIPTION_DEFAULT_PLAN', 'prosper'),
    'default_cycle' => env('SUBSCRIPTION_DEFAULT_CYCLE', 'monthly'),
    'catch_up_strategy' => env('SUBSCRIPTION_CATCH_UP', 'latest'), // latest | full
];

