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
        // Correcting the path for loading migrations from src/database/migrations
        $this->loadMigrationsFrom(__DIR__.'/database/migrations');

        // Load routes from src/routes/web.php
        $this->loadRoutesFrom(__DIR__.'/routes/web.php');

        // Correcting the paths for loading views from src/resources/views
        $this->loadViewsFrom(__DIR__.'/resources/views', 'shieldify');

        // Correcting the paths for publishing views to the application's resources/views/vendor/shieldify directory
        $this->publishes([
            __DIR__.'/resources/views' => resource_path('views/vendor/shieldify'),
        ], 'shieldify-views');

        // Correcting the paths for publishing assets to the application's public/vendor/shieldify directory
        $this->publishes([
            __DIR__.'/resources/assets' => public_path('vendor/shieldify'),
        ], 'shieldify-assets');

        // Publishing the configuration file from src/config/shieldify.php to the application's config directory
        $this->publishes([
            __DIR__.'/config/shieldify.php' => config_path('shieldify.php'),
        ], 'shieldify-config');

        // Merging the package configuration file with the application's configuration
        $this->mergeConfigFrom(__DIR__.'/config/shieldify.php', 'shieldify');

        // Register middleware aliases
        $router = $this->app['router'];
        $router->aliasMiddleware('shieldify.role', \Fortix\Shieldify\Middleware\CheckRole::class);
        $router->aliasMiddleware('shieldify.permission', \Fortix\Shieldify\Middleware\CheckPermission::class);
    }
}
