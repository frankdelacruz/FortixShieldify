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

composer require fortix/shieldify



## Manual Service Provider Registration

If Laravel's package auto-discovery does not automatically register the Shieldify service provider, manually add it to the `providers` array in your `config/app.php` file:


'providers' => [
...
Fortix\Shieldify\ShieldifyServiceProvider::class,
],




After installation, you may publish the package's assets with:

php artisan vendor:publish --provider="Fortix\Shieldify\ShieldifyServiceProvider"



This command publishes the configuration, migrations, and any other assets necessary for Shieldify to function. Run the migrations to set up the required database tables:


php artisan migrate




## Configuration

The `shieldify.php` configuration file will be located in your `config` directory after publishing. This file allows you to customize various aspects of Shieldify, including caching behavior for permissions to enhance performance.

## Basic Usage

### Defining Roles, Permissions, and Modules

Create roles, permissions, and modules directly using Eloquent models or through Shieldify Facades.

### Assigning Roles to Users

Easily assign roles to users to grant them the associated permissions.

### Checking Permissions

Perform permission checks to control access to application features.

## Middleware

Shieldify provides middleware for route protection based on roles and permissions.

## Advanced Usage

Refer to the full documentation for advanced features and customization options, including dynamic permission checks, role hierarchies, and more.

## Support

For support, please open an issue in the [GitHub repository](https://github.com/frankdelacruz/FortixShieldify).


