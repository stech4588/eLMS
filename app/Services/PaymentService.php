<?php


namespace App\Services;

use Illuminate\Support\Facades\Log;
use Stripe\PaymentIntent;
use Stripe\Stripe;

class PaymentService
{
    public function __construct()
    {
        $secretKey = config('services.stripe.secret');

        if (empty($secretKey)) {
            Log::channel('payment')->error('Stripe secret key is not configured.');
            throw new \RuntimeException('Stripe secret key is missing.');
        }

        Stripe::setApiKey($secretKey);
    }

    public function setupIntent($amount)
    {
        $amountInCents = (int) round(floatval($amount));

        if ($amountInCents <= 0) {
            throw new \InvalidArgumentException('Amount must be greater than zero.');
        }

        try {
            return PaymentIntent::create([
                'amount' => $amountInCents,
                'currency' => 'usd',
                'payment_method_types' => ['card'],
            ]);
        } catch (\Stripe\Exception\ApiErrorException $e) {
            // Log Stripe-specific error
            Log::channel('payment')->error('Stripe API error while creating intent', [
                'amount' => $amountInCents,
                'message' => $e->getMessage(),
                'code' => $e->getStripeCode(),
                'type' => $e->getError()->type ?? null,
            ]);
            throw new \Exception('Unable to create payment intent.'); // Re-throwing to handle it in fetchIntent
        } catch (\Exception $e) {
            // Log general exceptions
            Log::channel('payment')->error('General payment intent error', [
                'amount' => $amountInCents,
                'message' => $e->getMessage(),
                'code' => $e->getCode(),
            ]);
            throw $e; // Re-throwing to let the calling function handle it
        }
    }
}
