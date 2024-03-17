<?php

use Illuminate\Support\Facades\Route;
use Fortix\Shieldify\Http\Controllers\Api\RolesApiController;
use Fortix\Shieldify\Http\Controllers\Api\PermissionsApiController;
use Fortix\Shieldify\Http\Controllers\Api\ModulesApiController;

Route::prefix('api')->middleware('api')->group(function () {
    // Resource routes for Roles, Permissions, and Modules using API Controllers
    Route::apiResource('roles', RolesApiController::class);
    Route::apiResource('permissions', PermissionsApiController::class);
    Route::apiResource('modules', ModulesApiController::class);

    // Additional API routes for role-specific functionalities
    Route::get('/permissions/forRole/{role}', [PermissionsApiController::class, 'getPermissionsForRole'])->name('api.permissions.forRole');
    Route::post('/save-module-permissions', [PermissionsApiController::class, 'saveModulePermissions'])->name('api.saveModulePermissions');

    // API Routes for assigning users to roles
    Route::get('/roles/{roleId}/assign-users', [RolesApiController::class, 'showAssignUsersForm'])->name('api.roles.assign_users');
    Route::post('/roles/{roleId}/assign-users', [RolesApiController::class, 'assignUsers'])->name('api.roles.assign_users.process');
});
