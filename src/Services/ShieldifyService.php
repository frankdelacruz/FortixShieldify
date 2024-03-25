<?php

namespace Fortix\Shieldify\Services;

use Illuminate\Support\Facades\Cache;
use Fortix\Shieldify\Models\Permission;
use Fortix\Shieldify\Models\Role;
use Fortix\Shieldify\Models\Module;
use App\Models\User;

class ShieldifyService
{
    protected $user;
    protected $roleId;
    protected $moduleId;
    protected $useCache;

    public function __construct()
    {
        // Set default caching behavior based on configuration
        $this->useCache = config('shieldify.use_cache', true);
    }

    // Enable caching for the next operation
    public function withCache()
    {
        $this->useCache = true;
        return $this;
    }

    // Disable caching for the next operation
    public function withoutCache()
    {
        $this->useCache = false;
        return $this;
    }


    public function setUser(User $user)
    {
        $this->user = $user;
        return $this;
    }



    public function role($roleName)
    {
        if ($this->user) {
            // If a user is set, check if the user has the role by name
            $role = $this->user->roles()->where('name', $roleName)->firstOrFail();
        } else {
            // If no user is set, fetch the role by name as before
            $role = Role::where('name', $roleName)->firstOrFail();
        }
        $this->roleId = $role->id;
        return $this;
    }

    public function module($moduleName)
    {
        $module = Module::where('name', $moduleName)->firstOrFail();
        $this->moduleId = $module->id;
        return $this;
    }


    public function grantPermission($permissions)
    {
        $permissions = is_array($permissions) ? $permissions : [$permissions];
        $existingPermissions = Permission::where('role_id', $this->roleId)->where('module_id', $this->moduleId)->first();

        if ($existingPermissions) {
            $currentPermissions = json_decode($existingPermissions->permissions, true);
            $updatedPermissions = array_unique(array_merge($currentPermissions, $permissions));
            $existingPermissions->permissions = json_encode($updatedPermissions);
            $existingPermissions->save();
        } else {
            Permission::create([
                'role_id' => $this->roleId,
                'module_id' => $this->moduleId,
                'permissions' => json_encode($permissions)
            ]);
        }

        if ($this->useCache) {
            $cacheKey = "permissions_role_{$this->roleId}_module_{$this->moduleId}";
            Cache::put($cacheKey, $permissions, config('shieldify.cache_duration', 3600));
        }

        return $this;
    }




    public function revokePermission($permissions)
    {
        $permissions = is_array($permissions) ? $permissions : [$permissions];
        $permissionRecord = Permission::where('role_id', $this->roleId)->where('module_id', $this->moduleId)->first();

        if ($permissionRecord) {
            $currentPermissions = json_decode($permissionRecord->permissions, true);
            $updatedPermissions = array_diff($currentPermissions, $permissions);

            if (empty($updatedPermissions)) {
                $permissionRecord->delete();
            } else {
                $permissionRecord->permissions = json_encode($updatedPermissions);
                $permissionRecord->save();
            }

            if ($this->useCache) {
                $cacheKey = "permissions_role_{$this->roleId}_module_{$this->moduleId}";
                Cache::forget($cacheKey);
            }
        }

        return $this;
    }






    public function hasPermission($permissions)
    {
        $permissions = is_array($permissions) ? $permissions : [$permissions];

        if ($this->roleId && $this->moduleId) {
            // Check permissions for a specific role-module combination
            return $this->evaluatePermissions($permissions, $this->roleId, $this->moduleId);
        } else {
            // Check permissions for the logged-in user across all roles and modules
            return $this->evaluatePermissionsForUser($permissions, auth()->user());
        }
    }





    public function checkPermission($permissions)
    {
        if (!$this->hasPermission($permissions)) {
            abort(403, 'Unauthorized action.'); // Or any other exception handling mechanism
        }

        return $this; // Allows for method chaining if needed
    }





    protected function evaluatePermissions($permissions, $roleId, $moduleId)
    {
        $cacheKey = "permissions_role_{$roleId}_module_{$moduleId}";
        $grantedPermissions = $this->fetchPermissionsFromCacheOrDatabase($cacheKey, $roleId, $moduleId);

        $missingPermissions = array_diff($permissions, $grantedPermissions);
        return count($missingPermissions) === 0; // True if no permissions are missing, false otherwise
    }





    protected function evaluatePermissionsForUser($permissions, $user)
    {
        $userRoles = $user->roles->pluck('id')->toArray();
        $grantedPermissions = [];

        foreach ($userRoles as $roleId) {
            foreach ($user->modules as $module) {
                $cacheKey = "permissions_user_" . $user->id . "_role_{$roleId}_module_{$module->id}";
                $grantedPermissionsForRoleModule = $this->fetchPermissionsFromCacheOrDatabase($cacheKey, $roleId, $module->id);
                $grantedPermissions = array_merge($grantedPermissions, $grantedPermissionsForRoleModule);
            }
        }

        $grantedPermissions = array_unique($grantedPermissions);
        $missingPermissions = array_diff($permissions, $grantedPermissions);

        return count($missingPermissions) === 0; // True if no permissions are missing, false otherwise
    }





    protected function fetchPermissionsFromCacheOrDatabase($cacheKey, $roleId, $moduleId)
    {
        return Cache::remember($cacheKey, config('shieldify.cache_duration', 3600), function () use ($roleId, $moduleId) {
            $permissionRecord = Permission::where('role_id', $roleId)->where('module_id', $moduleId)->first();
            return $permissionRecord ? json_decode($permissionRecord->permissions, true) : [];
        });
    }




        protected function modulePermissionsForRole()
        {
            $permissionRecord = Permission::where('role_id', $this->roleId)->where('module_id', $this->moduleId)->first();
            return $permissionRecord ? json_decode($permissionRecord->permissions, true) : [];
        }




        //Roles

        public function createRole($name)
        {
            if (Role::where('name', $name)->exists()) {
                throw new \Exception("Role '{$name}' already exists.");
            }
            return Role::create(['name' => $name]);
        }
    
        public function updateRole($oldName, $newName)
        {
            $role = Role::where('name', $oldName)->firstOrFail();
            if (Role::where('name', $newName)->exists()) {
                throw new \Exception("Role '{$newName}' already exists.");
            }
            $role->name = $newName;
            $role->save();
            return $role;
        }


        public function deleteRole($name)
        {
            $role = Role::where('name', $name)->first();
            if (!$role) {
                throw new \Exception("Role '{$name}' does not exist.");
            }
            
            // Detach the role from all users
            $role->users()->detach();
            
            // Now delete the role
            $role->delete();
            return $this;
        }


        public function getAllRoles()
        {
            return Role::all();
        }


        public function assignRoleToUser($userIdentifier, $roleTitle)
         {
            $user = User::where('id', $userIdentifier)
                        ->orWhere('username', $userIdentifier)
                        ->orWhere('email', $userIdentifier)
                        ->firstOrFail();

            $role = Role::where('name', $roleTitle)->firstOrFail();

            // Check if the user already has the role
            if (!$user->roles->contains($role->id)) {
                // Attach the role to the user if they don't already have it
                $user->roles()->attach($role->id);
            }

            return $this; // Allows for method chaining if needed
        }



        public function hasRole($roleName)
        {
            if (!$this->user) {
                throw new \Exception("User not set in ShieldifyService.");
            }

            return $this->user->roles()->where('name', $roleName)->exists();
        }



        public function userHasRole($userIdentifier, $roleTitle)
        {
            $user = User::where('id', $userIdentifier)
                        ->orWhere('username', $userIdentifier)
                        ->orWhere('email', $userIdentifier)
                        ->first();

            if ($user) {
                return $user->roles()->where('name', $roleTitle)->exists();
            }

            return false;
        }



        public function getAllModulePermissions($roleName = null)
        {
            $permissionsList = [];

            if ($roleName) {
                // Fetch permissions for the specified role across all modules
                $role = Role::where('name', $roleName)->firstOrFail();
                $permissions = Permission::where('role_id', $role->id)->get();
            } else {
                // Fetch permissions for the currently logged-in user across all roles and modules
                $user = auth()->user();
                if (!$user) {
                    return []; // or handle this case as needed
                }
                $roleIds = $user->roles->pluck('id');
                $permissions = Permission::whereIn('role_id', $roleIds)->get();
            }

            foreach ($permissions as $permission) {
                $module = Module::find($permission->module_id);
                if ($module) {
                    $modulePermissions = json_decode($permission->permissions, true);
                    if (!isset($permissionsList[$module->name])) {
                        $permissionsList[$module->name] = $modulePermissions;
                    } else {
                        // Merge and remove duplicate permissions
                        $permissionsList[$module->name] = array_unique(array_merge($permissionsList[$module->name], $modulePermissions));
                    }
                }
            }

            return $permissionsList;
        }

    //Modules
    public function createModule($name)
    {
        if (Module::where('name', $name)->exists()) {
            throw new \Exception("Module '{$name}' already exists.");
        }
        return Module::create(['name' => $name]);
    }

    public function updateModule($oldName, $newName)
    {
        $module = Module::where('name', $oldName)->firstOrFail();
        if (Module::where('name', $newName)->exists()) {
            throw new \Exception("Module '{$newName}' already exists.");
        }
        $module->name = $newName;
        $module->save();
        return $module;
    }


    public function deleteModule($name)
    {
        $module = Module::where('name', $name)->first();
        if (!$module) {
            throw new \Exception("Module '{$name}' does not exist.");
        }
        
        // Retrieve all permissions associated with the module
        $permissions = Permission::where('module_id', $module->id)->get();
        foreach ($permissions as $permission) {
            // Construct the cache key used for storing permissions of this module
            $cacheKey = 'permissions_for_module_' . $module->id;

            // Check if permissions for this module are cached, and if so, clear the cache
            if (Cache::has($cacheKey)) {
                Cache::forget($cacheKey);
            }

            // Delete the permission record
            $permission->delete();
        }

        // Now delete the module itself
        $module->delete();
        return $this;
    }


    public function getAllModules()
    {
        return Module::all();
    }

}
