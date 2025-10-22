<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->configureLoginRateLimiting();
        //
    }

    protected function configureLoginRateLimiting()
    {
        RateLimiter::for('login', function () {
            return Limit::perMinute(5)->by(request()->ip())
                ->response(function () {
                    return redirect()->back()
                        ->withInput()
                        ->withErrors(['username' => 'Too many login attempts. Please try again in a minute.']);
                });
        });
    }
}
