<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!auth()->check()) {
            abort(401, 'Unauthorized. Please log in.');
        }

        $user = auth()->user();
        
        if (!$user->isAdmin()) {
            // Log unauthorized access attempts
            \Log::warning('Unauthorized admin access attempt', [
                'user_id' => $user->id,
                'user_email' => $user->email,
                'ip' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'path' => $request->path()
            ]);
            
            abort(403, 'Access denied. Only administrators can access this area.');
        }

        // Log successful admin access for audit trail
        \Log::info('Admin access granted', [
            'user_id' => $user->id,
            'user_email' => $user->email,
            'ip' => $request->ip(),
            'path' => $request->path()
        ]);

        return $next($request);
    }
}
