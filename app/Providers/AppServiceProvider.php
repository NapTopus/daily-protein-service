<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        RateLimiter::for('api', function (Request $request) {
            $userKey = optional($request->user())->getAuthIdentifier();
            $key     = $userKey ? "uid:{$userKey}" : "ip:" . $request->ip();

            return Limit::perMinute(120)->by($key);
        });

        RateLimiter::for('writes', function (Request $request) {
            $userKey = optional($request->user())->getAuthIdentifier();
            $key     = $userKey ? "uid:{$userKey}" : "ip:" . $request->ip();

            return Limit::perMinute(30)->by($key);
        });

        RateLimiter::for('login', function (Request $request) {
            $key = 'ip:' . ($request->ip());
            return Limit::perMinute(5)->by($key);
        });

        RateLimiter::for('register', function (Request $request) {
            $key = 'ip:' . ($request->ip());
            return [
                Limit::perMinute(3)->by($key),
                Limit::perHour(10)->by($key),
            ];
        });

        RateLimiter::for('refresh-token', function (Request $request) {
            $token = $request->cookie('refreshToken');

            if (!$token) {
                $key = 'ip:' . ($request->ip());
                return [
                    Limit::perMinute(60)->by($key),
                ];
            }

            $key = 'refresh:token:' . hash('sha256', $token);
            return [
                Limit::perMinute(5)->by($key),
            ];
        });
    }
}
