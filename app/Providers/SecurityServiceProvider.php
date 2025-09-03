<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;

class SecurityServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        $this->configureRateLimiting();
        $this->configureGates();
    }

    /**
     * Configure rate limiting for the application.
     */
    protected function configureRateLimiting(): void
    {
        // API rate limiting
        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(60)->by($request->user()?->id ?: $request->ip());
        });

        // Authentication rate limiting
        RateLimiter::for('auth', function (Request $request) {
            return Limit::perMinute(5)->by($request->ip());
        });

        // Admin rate limiting
        RateLimiter::for('admin', function (Request $request) {
            return Limit::perMinute(30)->by($request->user()?->id ?: $request->ip());
        });

        // General rate limiting
        RateLimiter::for('general', function (Request $request) {
            return Limit::perMinute(120)->by($request->ip());
        });
    }

    /**
     * Configure authorization gates.
     */
    protected function configureGates(): void
    {
        // Admin access gate
        Gate::define('admin-access', function ($user) {
            return $user->isAdmin();
        });

        // User management gate
        Gate::define('manage-users', function ($user) {
            return $user->isAdmin();
        });

        // Network provisioning gate
        Gate::define('network-provisioning', function ($user) {
            return $user->isAdmin() || $user->isUser();
        });

        // Profile management gate
        Gate::define('manage-profile', function ($user, $profileUser = null) {
            if ($user->isAdmin()) {
                return true;
            }
            
            return $profileUser && $user->id === $profileUser->id;
        });
    }
}
