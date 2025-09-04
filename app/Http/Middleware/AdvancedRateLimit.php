<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class AdvancedRateLimit
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $key = $this->resolveRequestSignature($request);
        
        // Simple rate limiting - 100 requests per minute for all routes
        $maxAttempts = 100;
        $decayMinutes = 1;

        if (RateLimiter::tooManyAttempts($key, $maxAttempts)) {
            $seconds = RateLimiter::availableIn($key);
            
            if ($request->expectsJson()) {
                return response()->json([
                    'error' => 'Too many requests. Please try again later.',
                    'retry_after' => $seconds
                ], 429);
            }

            return response('Too many requests. Please try again later.', 429)
                ->header('Retry-After', $seconds);
        }

        RateLimiter::hit($key, $decayMinutes * 60);

        return $next($request);
    }

    /**
     * Resolve request signature.
     */
    protected function resolveRequestSignature(Request $request): string
    {
        $identifier = $request->ip();
        
        // Add user ID if authenticated for more granular rate limiting
        if ($request->user()) {
            $identifier .= '|' . $request->user()->id;
        }
        
        // Add route path for different rate limits per endpoint
        $identifier .= '|' . $request->path();
        
        return sha1($identifier);
    }
}
