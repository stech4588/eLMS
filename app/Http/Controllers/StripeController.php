<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
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
            Log::channel('payment')->error('Error fetching payment intent', [
                'message' => $e->getMessage(),
                'amount' => $amount,
                'trace_id' => $e->getCode(),
            ]);
            return response()->json(['error' => 'Unable to create payment intent. Please try again later.'], 500);
        }
    }

    public function logPaymentError(Request $request)
    {
        $validated = $request->validate([
            'source' => 'required|string|in:frontend,backend',
            'stage' => 'nullable|string|max:255',
            'message' => 'required|string',
            'code' => 'nullable|string|max:255',
            'payload' => 'nullable|array',
        ]);

        $context = array_filter([
            'source' => $validated['source'],
            'stage' => $validated['stage'] ?? null,
            'code' => $validated['code'] ?? null,
            'payload' => $validated['payload'] ?? null,
            'ip' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ], function ($value) {
            return $value !== null && $value !== '';
        });

        Log::channel('payment')->error($validated['message'], $context);

        return response()->json(['logged' => true]);
    }
}
