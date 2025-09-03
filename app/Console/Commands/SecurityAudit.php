<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\DB;

class SecurityAudit extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'security:audit';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Perform a comprehensive security audit of the application';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🔒 Starting Security Audit...');
        $this->newLine();

        $this->checkEnvironment();
        $this->checkConfiguration();
        $this->checkDatabase();
        $this->checkFilePermissions();
        $this->checkDependencies();

        $this->newLine();
        $this->info('✅ Security audit completed!');
    }

    /**
     * Check environment configuration.
     */
    protected function checkEnvironment(): void
    {
        $this->info('📋 Environment Configuration:');
        
        $checks = [
            'APP_ENV' => env('APP_ENV'),
            'APP_DEBUG' => env('APP_DEBUG'),
            'APP_KEY' => env('APP_KEY') ? 'Set' : 'Not Set',
            'FORCE_HTTPS' => env('FORCE_HTTPS'),
            'SESSION_SECURE_COOKIE' => env('SESSION_SECURE_COOKIE'),
        ];

        foreach ($checks as $key => $value) {
            $status = $this->getStatusIcon($key, $value);
            $this->line("  {$status} {$key}: {$value}");
        }
    }

    /**
     * Check application configuration.
     */
    protected function checkConfiguration(): void
    {
        $this->newLine();
        $this->info('⚙️ Application Configuration:');
        
        $config = Config::get('session');
        $this->line("  📊 Session Lifetime: {$config['lifetime']} minutes");
        $this->line("  🔐 Session Encryption: " . ($config['encrypt'] ? 'Enabled' : 'Disabled'));
        $this->line("  🍪 Same Site: {$config['same_site']}");
        $this->line("  🔒 HTTP Only: " . ($config['http_only'] ? 'Yes' : 'No'));
    }

    /**
     * Check database security.
     */
    protected function checkDatabase(): void
    {
        $this->newLine();
        $this->info('🗄️ Database Security:');
        
        try {
            $connection = DB::connection();
            $this->line("  ✅ Database connection: OK");
            
            // Check if SSL is enabled for MySQL
            if ($connection->getDriverName() === 'mysql') {
                $sslMode = $connection->getPdo()->getAttribute(\PDO::ATTR_SSL_VERIFY_SERVER_CERT);
                $this->line("  🔐 SSL Verification: " . ($sslMode ? 'Enabled' : 'Disabled'));
            }
        } catch (\Exception $e) {
            $this->line("  ❌ Database connection: Failed - {$e->getMessage()}");
        }
    }

    /**
     * Check file permissions.
     */
    protected function checkFilePermissions(): void
    {
        $this->newLine();
        $this->info('📁 File Permissions:');
        
        $paths = [
            storage_path() => '0755',
            storage_path('logs') => '0755',
            storage_path('framework') => '0755',
            base_path('.env') => '0644',
        ];

        foreach ($paths as $path => $expected) {
            if (File::exists($path)) {
                $permission = substr(sprintf('%o', fileperms($path)), -4);
                $status = $permission === $expected ? '✅' : '⚠️';
                $this->line("  {$status} {$path}: {$permission} (expected: {$expected})");
            }
        }
    }

    /**
     * Check dependencies for known vulnerabilities.
     */
    protected function checkDependencies(): void
    {
        $this->newLine();
        $this->info('📦 Dependencies:');
        
        if (File::exists(base_path('composer.lock'))) {
            $this->line("  📋 Composer.lock exists - run 'composer audit' for vulnerability check");
        }
        
        if (File::exists(base_path('package-lock.json'))) {
            $this->line("  📋 Package-lock.json exists - run 'npm audit' for vulnerability check");
        }
    }

    /**
     * Get status icon based on configuration value.
     */
    protected function getStatusIcon(string $key, $value): string
    {
        if ($key === 'APP_DEBUG') {
            return $value ? '⚠️' : '✅';
        }
        
        if ($key === 'APP_KEY') {
            return $value === 'Set' ? '✅' : '❌';
        }
        
        if (in_array($key, ['FORCE_HTTPS', 'SESSION_SECURE_COOKIE'])) {
            return $value ? '✅' : '⚠️';
        }
        
        return 'ℹ️';
    }
}
