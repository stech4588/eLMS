<?php


namespace App\Services;

use Stripe\Stripe;

class PaymentService
{
    public function __construct()
    {
        Stripe::setApiKey(env("STRIPE_SECRET"));
    }
   public function setupIntent($amount)
    {
        try {
                                    

            $amount = intval(floatval($amount) * 100);
            return \Stripe\PaymentIntent::create([
                'amount' => $amount,
                'currency' => 'usd',
                'payment_method_types' => ['card']
            ]);
        } catch (\Stripe\Exception\ApiErrorException $e) {
            // Log Stripe-specific error
            \Log::error('Stripe API error: ' . $e->getMessage());
            throw new \Exception('Unable to create payment intent.'); // Re-throwing to handle it in fetchIntent
        } catch (\Exception $e) {
            // Log general exceptions
            \Log::error('General error: ' . $e->getMessage());
            throw $e; // Re-throwing to let the calling function handle it
        }
    }
    
}
