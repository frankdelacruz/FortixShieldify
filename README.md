# Shieldify Package for Laravel

Shieldify is a comprehensive Laravel package designed to simplify role, permission, and module management within your application. It offers a fluent, easy-to-use API for attaching permissions to roles, roles to users, and conducting permission checks for user authorization.

## Table of Contents

- [Installation](#installation)
- [Manual Service Provider Registration](#manual-service-provider-registration)
- [Configuration](#configuration)
- [Basic Usage](#basic-usage)
  - [Defining Roles, Permissions, and Modules](#defining-roles-permissions-and-modules)
  - [Assigning Roles to Users](#assigning-roles-to-users)
  - [Checking Permissions](#checking-permissions)
- [Middleware](#middleware)
- [Advanced Usage](#advanced-usage)
- [Support](#support)

## Installation

To install Shieldify, run the following command in your project directory:


`composer require fortix/shieldify`


##  Manual Service Provider Registration


If Laravel's package auto-discovery does not automatically register the Shieldify service provider, manually add it to the providers array in your config/app.php file:


    'providers' => [
        ...
        Fortix\Shieldify\ShieldifyServiceProvider::class,
    ],
    

### After installation, you may publish the package's assets with:

    php artisan vendor:publish --provider="Fortix\Shieldify\ShieldifyServiceProvider"


This command publishes the configuration, migrations, and any other assets necessary for Shieldify to function. Run the migrations to set up the required database tables:

php artisan migrate



## Configuration

The `shieldify.php` configuration file will be located in your config directory after publishing. This file allows you to customize various aspects of Shieldify, including caching behavior for permissions to enhance performance.


    <?php
    
    return [
        // Whether to use cache for storing permissions and roles
        'use_cache' => env('SHIELDFY_USE_CACHE', true),
    
        // Default context for the package usage. Possible values: 'web', 'api', 'both'
        'context' => env('SHIELDFY_CONTEXT', 'web'),
    
        // Cache duration in minutes. Determines how long permissions and roles are stored in cache
        'cache_duration' => env('SHIELDFY_CACHE_DURATION', 60),
    
        // Other configuration values can be added here as needed
    ];
    




## Basic Usage

##### DEFINING ROLES, PERMISSIONS, AND MODULES
###### Create roles, permissions, and modules directly using Eloquent models or through Shieldify Facades.


    use Fortix\Shieldify\Models\{Role, Permission, Module};
    
    // Create a role
    $role = Role::create(['name' => 'Editor']);
    
    // Create a module
    $module = Module::create(['name' => 'Articles']);
    
    // Assign permissions to the role for the module
    Shieldify::role('Editor')->module('Articles')->grantPermission(['edit', 'delete']);


##### Assigning Roles to Users
##### Easily assign roles to users to grant them the associated permissions.


    $user = User::find(1); // Assuming an existing user
    Shieldify::assignRoleToUser($user->id, 'Editor');


##  Checking Permissions
##### Perform permission checks to control access to application features.


    if (Shieldify::module('Articles')->hasPermission('edit')) {
        // The user has permission to edit articles
    }


## Middleware
Shieldify provides middleware for route protection based on roles and permissions.


######  In your app/Http/Kernel.php
    protected $routeMiddleware = [
        'role' => \Fortix\Shieldify\Http\Middleware\EnsureRole::class,
        'permission' => \Fortix\Shieldify\Http\Middleware\EnsurePermission::class,
    ];
    

###### In your routes/web.php or routes/api.php
    Route::middleware(['role:Editor'])->group(function () {
        // Routes accessible only by users with the 'Editor' role
    });
    
    Route::middleware(['permission:edit Articles'])->group(function () {
        // Routes requiring 'edit' permission on 'Articles' module
    });



## Advanced Usage
Refer to the full documentation for advanced features and customization options, including dynamic permission checks, role hierarchies, and more.


## Support
For support, please open an issue in the GitHub repository.