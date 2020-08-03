<?php

namespace Extensions\Plugins\Support_alfiory__930442654\Providers;

use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * This namespace is applied to your controller routes.
     *
     * @var string
     */
    protected $routeNamespace = 'Extensions\Plugins\Support_alfiory__930442654\App\Controllers';

    /**
     * Define your route model bindings, pattern filters, etc.
     *
     * @return void
     */
    public function boot()
    {
        //

        parent::boot();
    }

    /**
     * Define the routes for the application.
     *
     * @return void
     */
    public function map()
    {
        $this->mapMainRoutes();
        $this->mapAdminRoutes();
        $this->mapApiRoutes();
    }

    /**
     * Define the "web" routes for the application.
     *
     * These routes all receive session state, CSRF protection, etc.
     *
     * @return void
     */
    protected function mapMainRoutes()
    {
        Route::middleware(['web', 'ICheck'])
            ->namespace($this->routeNamespace)
            ->group(str_replace('Providers', '', __DIR__) . 'Routes/routes.php');
    }

    protected function mapAdminRoutes()
    {
        Route::middleware(['web', 'ICheck', 'RIfUserIsLogout', 'role_or_perm:admin|ACCESS__DASHBOARD'])
            ->prefix('dashboard')
            ->namespace($this->routeNamespace)
            ->group(str_replace('Providers', '', __DIR__) . 'Routes/admin.php');
    }

    protected function mapApiRoutes()
    {
        Route::middleware(['api', 'ICheck'])
            ->namespace($this->routeNamespace)
            ->group(str_replace('Providers', '', __DIR__) . 'Routes/api.php');
    }
}
