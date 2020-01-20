<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * The path to the "home" route for your application.
     *
     * @var string
     */
    public const HOME = '/home';

    /**
     * Define your route model bindings, pattern filters, etc.
     *
     * @return void
     */
    public function boot()
    {
        //
        $this->configureRateLimiting();

        RateLimiter::for("download", function ($request) {
            return Limit::perMinute(60);
        });

        parent::boot();
    }

    /**
     * Define the routes for the application.
     *
     * @return void
     */
    public function map()
    {
        $this->mapApiRoutes();

        Route::middleware(['web', 'HtmlMinifier'])->group(function () {
            $this->mapWebRoutes();

            Route::middleware('fw-block-blacklisted')->group(function () {
                $this->mapAdminRoutes();

                $this->mapUserRoutes();

                $this->mapEmployeeRoutes();

                $this->mapClientRoutes();
            });
        });

        Route::middleware('fw-block-blacklisted')->group(function () {
            $this->ipnRoutes();
        });
    }

    /**
     * Define the "web" routes for the application.
     *
     * These routes all receive session state, CSRF protection, etc.
     *
     * @return void
     */
    protected function mapWebRoutes()
    {
        Route::namespace($this->namespace)
            ->middleware(['cookie-consent'])
            ->group(base_path('routes/web.php'));
    }

    protected function mapAdminRoutes()
    {
        Route::middleware(['auth','role:admin'])
            ->group(base_path('routes/admin.php'));
    }

    protected function mapUserRoutes()
    {
        Route::namespace($this->namespace)
            ->group(base_path('routes/user.php'));
    }

    protected function mapEmployeeRoutes()
    {
        Route::middleware(['auth', 'verified', 'role:employee'])
            ->group(base_path('routes/employee.php'));
    }

    protected function mapClientRoutes()
    {
        Route::middleware(['auth', 'verified', 'role:client'])
            ->group(base_path('routes/client.php'));
    }

    /**
     * Define the "api" routes for the application.
     *
     * These routes are typically stateless.
     *
     * @return void
     */
    protected function mapApiRoutes()
    {
        Route::prefix('api')
            ->as('api.')
            ->middleware(['api','cors'])
            ->group(base_path('routes/api.php'));
    }

    protected function ipnRoutes()
    {
        Route::namespace($this->namespace)
            ->group(base_path('routes/ipn.php'));
    }

    public function configureRateLimiting()
    {
        RateLimiter::for("download", function ($request) {
            return Limit::perMinute(2);
        });
    }
}
