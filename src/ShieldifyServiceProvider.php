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
        $context = config('shieldify.context', 'web');
    
        // Always load migrations
        $this->loadMigrationsFrom(__DIR__.'/database/migrations');
    
        // Conditionally load routes and publish resources based on context
        if ($context === 'api' || $context === 'both') {
            // Load API routes
            $this->loadRoutesFrom(__DIR__.'/routes/api.php');
    
            // Example: Publish API-specific configuration
            $this->publishes([
                __DIR__.'/config/api.php' => config_path('shieldify_api.php'),
            ], 'shieldify-api');
    
            // If you have API-specific views or assets, publish them here using the 'shieldify-api' tag
        }
        
        if ($context === 'web' || $context === 'both') {
            // Load web routes
            $this->loadRoutesFrom(__DIR__.'/routes/web.php');
            
            // Publish views and assets for web context
            $this->publishes([
                __DIR__.'/resources/views' => resource_path('views/vendor/shieldify'),
            ], 'shieldify-views');
    
            $this->publishes([
                __DIR__.'/resources/assets' => public_path('vendor/shieldify'),
            ], 'shieldify-assets');
        }
    
        // Always register configuration and middleware
        $this->publishes([
            __DIR__.'/config/shieldify.php' => config_path('shieldify.php'),
        ], 'shieldify-config');
        $this->mergeConfigFrom(__DIR__.'/config/shieldify.php', 'shieldify');
    
        $router = $this->app['router'];
        $router->aliasMiddleware('shieldify.role', \Fortix\Shieldify\Middleware\CheckRole::class);
        $router->aliasMiddleware('shieldify.permission', \Fortix\Shieldify\Middleware\CheckPermission::class);
    }
    
    
}