<?php

namespace App\Providers;

use App\Support\PublicUrl;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\URL;
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
        RateLimiter::for('candidate-submissions', function (Request $request): Limit {
            return Limit::perHour(5)->by($request->ip());
        });

        if (PublicUrl::shouldForceHttps()) {
            URL::forceRootUrl(PublicUrl::baseUrl());
            URL::forceScheme('https');
        }
    }
}
