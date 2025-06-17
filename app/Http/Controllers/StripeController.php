<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Stripe\Stripe;
use Stripe\PaymentIntent;
use Stripe\Customer;
use App\Services\PaymentService;

class StripeController extends Controller
{
    protected $paymentService;

    public function __construct(PaymentService $paymentService)
    {
        $this->paymentService = $paymentService;
    }
    public function fetchIntent($amount)
    {
        try {

            $setupIntent = $this->paymentService->setupIntent($amount);
            return response()->json($setupIntent, 200);
        } catch (\Exception $e) {
            // Log the error and return a JSON response with the error message
            \Log::error('Error fetching payment intent: ' . $e->getMessage());
            return response()->json(['error' => 'Unable to create payment intent. Please try again later.'], 500);
        }
    }
}
