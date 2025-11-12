<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Symfony\Component\HttpFoundation\Response;

class CheckCommunityAccess
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next)
    {
        $user = Auth::user();

        if ($user && ($user->can_view_community || $user->type === 'admin')) {
            return $next($request);
        }

        return Inertia::render('Error/AccessDenied', [
            'message' => 'Sorry, you do not have permission to access this page.'
        ]);
    }
}
