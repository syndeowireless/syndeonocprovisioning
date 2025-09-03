<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;

class SecurityMonitoringJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $this->checkFailedLogins();
        $this->checkSuspiciousActivity();
        $this->checkRateLimitViolations();
        $this->checkDatabaseConnections();
        $this->checkFileIntegrity();
    }

    /**
     * Check for failed login attempts.
     */
    protected function checkFailedLogins(): void
    {
        $failedLogins = Cache::get('failed_logins', []);
        $suspiciousIPs = [];

        foreach ($failedLogins as $ip => $attempts) {
            if (count($attempts) > 10) {
                $suspiciousIPs[] = $ip;
                Log::warning('Suspicious login activity detected', [
                    'ip' => $ip,
                    'failed_attempts' => count($attempts),
                    'last_attempt' => end($attempts)
                ]);
            }
        }

        if (!empty($suspiciousIPs)) {
            Log::alert('Multiple suspicious IPs detected', [
                'suspicious_ips' => $suspiciousIPs
            ]);
        }
    }

    /**
     * Check for suspicious activity patterns.
     */
    protected function checkSuspiciousActivity(): void
    {
        // Check for unusual access patterns
        $recentRequests = Cache::get('recent_requests', []);
        $suspiciousPatterns = [];

        foreach ($recentRequests as $ip => $requests) {
            $adminAccess = array_filter($requests, fn($r) => str_contains($r['path'], 'admin'));
            $apiAccess = array_filter($requests, fn($r) => str_contains($r['path'], 'api'));
            
            if (count($adminAccess) > 5 || count($apiAccess) > 20) {
                $suspiciousPatterns[] = [
                    'ip' => $ip,
                    'admin_requests' => count($adminAccess),
                    'api_requests' => count($apiAccess)
                ];
            }
        }

        if (!empty($suspiciousPatterns)) {
            Log::warning('Suspicious access patterns detected', [
                'patterns' => $suspiciousPatterns
            ]);
        }
    }

    /**
     * Check rate limit violations.
     */
    protected function checkRateLimitViolations(): void
    {
        $violations = Cache::get('rate_limit_violations', []);
        
        if (!empty($violations)) {
            Log::warning('Rate limit violations detected', [
                'violations' => $violations
            ]);
            
            // Clear old violations
            Cache::forget('rate_limit_violations');
        }
    }

    /**
     * Check database connection security.
     */
    protected function checkDatabaseConnections(): void
    {
        try {
            $connection = DB::connection();
            
            if ($connection->getDriverName() === 'mysql') {
                $sslMode = $connection->getPdo()->getAttribute(\PDO::ATTR_SSL_VERIFY_SERVER_CERT);
                
                if (!$sslMode) {
                    Log::warning('Database SSL verification is disabled');
                }
            }
        } catch (\Exception $e) {
            Log::error('Database security check failed', [
                'error' => $e->getMessage()
            ]);
        }
    }

    /**
     * Check file integrity.
     */
    protected function checkFileIntegrity(): void
    {
        $criticalFiles = [
            base_path('.env'),
            base_path('composer.json'),
            base_path('package.json'),
        ];

        foreach ($criticalFiles as $file) {
            if (!file_exists($file)) {
                Log::alert('Critical file missing', ['file' => $file]);
            } elseif (!is_readable($file)) {
                Log::warning('Critical file not readable', ['file' => $file]);
            }
        }
    }
}
