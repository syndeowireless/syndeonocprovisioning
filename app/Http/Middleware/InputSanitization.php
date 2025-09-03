<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class InputSanitization
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $this->sanitizeInput($request);
        return $next($request);
    }

    /**
     * Sanitize all input data.
     */
    protected function sanitizeInput(Request $request): void
    {
        // Sanitize GET parameters
        $this->sanitizeArray($request->query->all());
        
        // Sanitize POST parameters
        $this->sanitizeArray($request->post->all());
        
        // Sanitize JSON input
        if ($request->isJson()) {
            $jsonData = $request->json()->all();
            $this->sanitizeArray($jsonData);
            $request->json()->replace($jsonData);
        }
    }

    /**
     * Recursively sanitize array values.
     */
    protected function sanitizeArray(array &$data): void
    {
        foreach ($data as $key => &$value) {
            if (is_array($value)) {
                $this->sanitizeArray($value);
            } elseif (is_string($value)) {
                $value = $this->sanitizeString($value);
            }
        }
    }

    /**
     * Sanitize individual string values.
     */
    protected function sanitizeString(string $value): string
    {
        // Remove null bytes
        $value = str_replace("\0", '', $value);
        
        // Remove control characters except newlines and tabs
        $value = preg_replace('/[\x00-\x08\x0B\x0C\x0E-\x1F\x7F]/', '', $value);
        
        // HTML encode potentially dangerous characters
        $value = htmlspecialchars($value, ENT_QUOTES | ENT_HTML5, 'UTF-8');
        
        return $value;
    }
}
