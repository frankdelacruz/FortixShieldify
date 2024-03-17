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
        // Assuming the database, routes, views, and assets are correctly located relative to the ServiceProvider

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

        // Publish the configuration file
        $this->publishes([
            // If your config is truly inside the src, this path needs to reflect that
            __DIR__.'/config/shieldify.php' => config_path('shieldify.php'),
        ], 'shieldify-config');

        // Merge the package configuration file with the application's configuration
        $this->mergeConfigFrom(
            // The path here assumes config is directly under src, as you indicated
            __DIR__.'/config/shieldify.php', 'shieldify'
        );

        // Register middleware aliases
        $router = $this->app['router'];
        $router->aliasMiddleware('shieldify.role', \Fortix\Shieldify\Middleware\CheckRole::class);
        $router->aliasMiddleware('shieldify.permission', \Fortix\Shieldify\Middleware\CheckPermission::class);
    }
}
