<?php

return [
    // Whether to use cache for storing permissions and roles
    'use_cache' => env('SHIELDFY_USE_CACHE', true),

    // Default context for the package usage. Possible values: 'web', 'api', 'both'
    'context' => env('SHIELDFY_CONTEXT', 'web'),

    // Cache duration in minutes. Determines how long permissions and roles are stored in cache
    'cache_duration' => env('SHIELDFY_CACHE_DURATION', 60),

    // Other configuration values can be added here as needed

      // Configuration for Model

      'models' => [
        'role' => Fortix\Shieldify\Models\Role::class,
        'permission' => Fortix\Shieldify\Models\Permission::class,
        'module' =>  Fortix\Shieldify\Models\Module::class,
    ],

    
];
