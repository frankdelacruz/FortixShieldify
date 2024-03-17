<?php

namespace Fortix\Shieldify;

use Illuminate\Support\ServiceProvider;

class ShieldifyServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton('shieldify', function ($app) {
            return new \Fortix\Shieldify\Services\ShieldifyService();
        });
    }

    public function boot()
    {
        // Assume your package's structure is standard and follows Laravel's conventions.
        // Adjust the paths to load migrations, routes, views, and configuration.

        // Load migrations
        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');

        // Load routes
        $this->loadRoutesFrom(__DIR__.'/routes/web.php');

        // Load views
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'shieldify');

        // Publish views
        $this->publishes([
            __DIR__.'/../resources/views' => resource_path('views/vendor/shieldify'),
        ], 'shieldify-views');

        // Publish assets
        $this->publishes([
            __DIR__.'/../resources/assets' => public_path('vendor/shieldify'),
        ], 'shieldify-assets');

        // Publish and merge configuration
        $this->publishes([
            __DIR__.'/../config/shieldify.php' => config_path('shieldify.php'),
        ], 'shieldify-config');
        $this->mergeConfigFrom(realpath(__DIR__.'/../config/shieldify.php'), 'shieldify');

        // Register middleware aliases
        $router = $this->app['router'];
        $router->aliasMiddleware('shieldify.role', \Fortix\Shieldify\Middleware\CheckRole::class);
        $router->aliasMiddleware('shieldify.permission', \Fortix\Shieldify\Middleware\CheckPermission::class);
    }
}
