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
        // Load routes, views, migrations, etc.


        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');

        $this->loadRoutesFrom(base_path('packages/Fortix/Shieldify/src/routes/web.php'));

        $this->loadViewsFrom(__DIR__.'/../resources/views', 'shieldify');

        $this->publishes([
            __DIR__.'/resources/views' => resource_path('views/vendor/shieldify'),
        ], 'shieldify-views');

        $this->publishes([
            __DIR__.'/resources/assets' => public_path('vendor/shieldify'),
        ], 'shieldify-assets');

        $this->publishes([
            __DIR__.'/config/shieldify.php' => config_path('shieldify.php'),
        ], 'shieldify-config');

        $this->mergeConfigFrom(
            __DIR__.'/config/shieldify.php', 'shieldify'
        );

        $router = $this->app['router'];
        $router->aliasMiddleware('shieldify.role', \Fortix\Shieldify\Middleware\CheckRole::class);
        $router->aliasMiddleware('shieldify.permission', \Fortix\Shieldify\Middleware\CheckPermission::class);

    }
}
