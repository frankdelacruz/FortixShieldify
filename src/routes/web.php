<?php

use Illuminate\Support\Facades\Route;
use Fortix\Shieldify\Http\Controllers\Web\RolesController;
use Fortix\Shieldify\Http\Controllers\Web\PermissionsController;
use Fortix\Shieldify\Http\Controllers\Web\ModulesController;

Route::middleware('web')->group(function () {
// Resource routes for Roles and Permissions
Route::resource('roles', RolesController::class);
Route::resource('permissions', PermissionsController::class);

//Module
Route::resource('modules', ModulesController::class);


// Additional routes for role-specific functionalities
Route::get('/permissions/forRole/{role}', [PermissionsController::class, 'getPermissionsForRole'])->name('permissions.forRole');
Route::post('/save-module-permissions', [PermissionsController::class, 'saveModulePermissions'])->name('saveModulePermissions');

// Routes for assigning users to roles
Route::get('/roles/{roleId}/assign-users', [RolesController::class, 'showAssignUsersForm'])->name('roles.assign_users');
Route::post('/roles/{roleId}/assign-users', [RolesController::class, 'assignUsers'])->name('roles.assign_users.process');

});

