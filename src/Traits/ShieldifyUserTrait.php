<?php

namespace Fortix\Shieldify\Traits;

use Fortix\Shieldify\Models\Role;
use Fortix\Shieldify\Models\Permission;
use Fortix\Shieldify\Models\Module;

trait ShieldifyUserTrait {
    /**
     * The roles that belong to the user.
     */
    public function roles() {
        // Adjust the relationship definition if necessary, based on your pivot table's structure
        return $this->belongsToMany(Role::class, 'role_user', 'user_id', 'role_id');
    }

    /**
     * The permissions that belong to the user through roles.
     */
    public function permissions() {
        return $this->hasManyThrough(
            Permission::class,
            Role::class,
            'user_id', // Foreign key on the Role model table
            'role_id', // Foreign key on the Permission model table
            'id', // Local key on the User model table
            'id' // Local key on the Role model table
        );
    }

    /**
     * Check if the user has a specific permission.
     */
    public function hasPermission($permission) {
        return $this->permissions()->where('permissions', 'like', '%' . $permission . '%')->exists();
    }

    /**
     * Check if the user has a specific role.
     */
    public function hasRole($roleName) {
        return $this->roles()->where('name', $roleName)->exists();
    }
}
