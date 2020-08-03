<?php

namespace Extensions\Plugins\Support_alfiory__930442654\Providers;

use App\System\Extensions\Plugin\Core\PluginServiceProvider as ServiceProvider;


class SupportServiceProvider extends ServiceProvider
{

    // Https://Docs.TrixCMS.Eu

    protected $pluginName = "Support_alfiory__930442654";

    protected $middleware = []; // Load simple middleware

    protected $middlewareGroups = []; // Load middleware groups

    protected $middlewarePriority = []; // Load priority middleware

    protected $middlewareRoute = []; // Load route middleware

    /**
     * Register any application services.
     *
     * @return void
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    public function register()
    {
        $this->registerAllMiddlewares(); // Register all middlewares
        $this->loadRoutes(); // Load all routes from RouteServiceProvider of this plugin
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadViews(); // Load all views from this plugin
        $this->loadMigrations(); // Load all migrations from this plugin
        $this->loadFactories(); // Load all factories from this plugin
        $this->loadTranslations(); // Load all translations from this plugin

        $this->registerAdminNavbar(); // Register admin navbar
        $this->registerUserRoutes(); // Register user navbar
    }
}