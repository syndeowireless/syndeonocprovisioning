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
        
        // Different rate limits for different types of requests
        if ($request->is('api/*')) {
            $maxAttempts = 60; // 60 requests per minute for API
            $decayMinutes = 1;
        } elseif ($request->is('auth/*')) {
            $maxAttempts = 5; // 5 attempts per minute for auth
            $decayMinutes = 1;
        } elseif ($request->is('admin/*')) {
            $maxAttempts = 30; // 30 requests per minute for admin
            $decayMinutes = 1;
        } else {
            $maxAttempts = 120; // 120 requests per minute for general pages
            $decayMinutes = 1;
        }

        if (RateLimiter::tooManyAttempts($key, $maxAttempts)) {
            $seconds = RateLimiter::availableIn($key);
            
            Log::warning('Rate limit exceeded', [
                'ip' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'path' => $request->path(),
                'seconds_remaining' => $seconds
            ]);

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

        $response = $next($request);

        return $response->header('X-RateLimit-Limit', $maxAttempts)
                       ->header('X-RateLimit-Remaining', RateLimiter::remaining($key, $maxAttempts));
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
