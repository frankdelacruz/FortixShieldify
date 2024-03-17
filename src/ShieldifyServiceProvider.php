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
        // Load migrations from the database/migrations directory
        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');

        // Load routes from the routes/web.php file
        $this->loadRoutesFrom(__DIR__.'/routes/web.php');

        // Load views from the resources/views directory and set the namespace
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'shieldify');

        // Publish views to the application's resources/views/vendor/shieldify directory
        $this->publishes([
            __DIR__.'/../resources/views' => resource_path('views/vendor/shieldify'),
        ], 'shieldify-views');

        // Publish assets to the application's public/vendor/shieldify directory
        $this->publishes([
            __DIR__.'/../resources/assets' => public_path('vendor/shieldify'),
        ], 'shieldify-assets');

        // Publish the configuration file to the application's config directory
        // Note: Adjusted the path to reflect the config directory's location inside src
        $this->publishes([
            __DIR__.'/config/shieldify.php' => config_path('shieldify.php'),
        ], 'shieldify-config');

        // Merge the package configuration file with the application's configuration
        // Note: realpath() is removed to avoid the error when the path does not resolve to a valid directory
        $this->mergeConfigFrom(__DIR__.'/config/shieldify.php', 'shieldify');

        // Register middleware aliases
        $router = $this->app['router'];
        $router->aliasMiddleware('shieldify.role', \Fortix\Shieldify\Middleware\CheckRole::class);
        $router->aliasMiddleware('shieldify.permission', \Fortix\Shieldify\Middleware\CheckPermission::class);
    }
}
