<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureSubscriptionIsActive
{
    protected array $allowedRoutes = [
        'invoices.*',
        'billing.*',
        'support.*',
        'register.from.payment',
        'fetch-intent',
        'payment-error-log',
    ];

    public function handle(Request $request, Closure $next): Response
    {
        if (!$request->user()) {
            return $next($request);
        }

        if ($request->routeIs($this->allowedRoutes)) {
            return $next($request);
        }

        $status = $request->user()->subscriptionStatus;

        if ($status && in_array($status->state, ['suspended', 'suspended_manual'], true)) {
            $message = 'Your account is suspended until payment is received.';

            if ($request->expectsJson()) {
                return response()->json([
                    'message' => $message,
                    'suspended' => true,
                ], 401);
            }

            return redirect()->route('billing.portal')
                ->withErrors(['subscription' => $message]);
        }

        return $next($request);
    }
}

