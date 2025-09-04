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
            abort(403, 'Access denied. Only administrators can access this area.');
        }

        return $next($request);
    }
}
