<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * Define your route model bindings, pattern filters, etc.
     */
    public function boot(): void
    {
        $this->configureRateLimiting();

        $this->routes(function () {
            
            Route::prefix('api')
                ->middleware('api')
                ->group(base_path('routes/api.php'));
            
            /*Route::middleware('web')
                ->group(base_path('routes/web.php'));*/
        });
    }

    /**
     * This function configures the rate limiting. If users send more than 100 requests in a minute,
     * they will get a "429 Too Many Requests" message.
     * It checks the user ID for logged users and the IP address for not logged users.
     */
    protected function configureRateLimiting(): void
    {
        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(100)->by($request->user()?->id ?: $request->ip());
        });
    }
}