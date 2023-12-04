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
     * The path to your application's "home" route.
     *
     * Typically, users are redirected here after authentication.
     *
     * @var string
     */
    public const HOME = '/';

    /**
     * The controller namespace for the application.
     *
     * When present, controller route declarations will automatically be prefixed with this namespace.
     *
     * @var string|null
     */
    protected $namespace = 'App\\Http\\Controllers';

    /**
     * Define your route model bindings, pattern filters, and other route configuration.
     */
    public function boot(): void
    {
        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(60)->by($request->user()?->id ?: $request->ip());
        });

        $this->routes(function () {
            Route::middleware('api')
                ->prefix('api')
                ->group(base_path('routes/api.php'));

            Route::middleware('web')
                ->group(base_path('routes/web.php'));

            $this->routeV100();
        });
    }


    /**
     * Configure Route V1
     *
     * @return void
     * @author Jan Allan Verano <janallanverano@gmail.com>
     * @mods
     *  JAV 20231002 - Created
     */
    private function routeV100()
    {
        Route::prefix('api/v1/')
            ->namespace($this->namespace . '\\V1')
            ->middleware(['auth:sanctum', 'api'])
            ->group(base_path('routes/v1/main.php'));

        Route::prefix('api/v1/')
            ->namespace($this->namespace . '\\V1')
            ->middleware(['api'])
            ->group(base_path('routes/v1/auth.php'));

    }
}
