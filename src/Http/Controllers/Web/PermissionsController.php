<?php

namespace Fortix\Shieldify\Http\Controllers\Web;

use Illuminate\Http\Request;
use Fortix\Shieldify\Models\Permission;
use Fortix\Shieldify\Models\Role;
use Fortix\Shieldify\Models\Module;
use App\Http\Controllers\Controller;

class PermissionsController extends Controller
{
    public function index()
    {

        $permissions = Permission::with(['role', 'module'])->paginate(10); // Adjust pagination as needed
        return view('shieldify::permissions.index', compact('permissions'));

    }


    public function create()
    {
        $roles = Role::all(); // Fetch all roles
        $modules = Module::all(); // Fetch all modules
        return view('shieldify::permissions.create', compact('roles', 'modules'));
    }


    public function store(Request $request)
    {
        $permission = Permission::create($request->all());
        return response()->json($permission, 201);
    }

    public function show(Permission $permission)
    {
        return response()->json($permission);
    }



    public function edit(Permission $permission)
    {
        return view('shieldify::permissions.edit', compact('permission'));
    }


    public function update(Request $request, Permission $permission)
    {
        $permission->update($request->all());
        return response()->json($permission);
    }

    public function destroy(Permission $permission)
    {
        $permission->delete();
        return response()->json(null, 204);
    }


    public function getPermissionsForRole($roleId)
    {
        $modules = Module::all(); // Get all modules
        $permissionsData = [];

        foreach ($modules as $module) {
            $permission = Permission::where('role_id', $roleId)
                                    ->where('module_id', $module->id)
                                    ->first(); // Assuming one permission record per role-module combination

            $modulePermissions = $permission ? json_decode($permission->permissions, true) : [];

            $permissionsData[] = [
                'id' => $module->id,
                'name' => $module->name,
                'permissions' => $modulePermissions, // This should be an array of permissions
            ];
        }

        return response()->json(['modules' => $permissionsData]);
    }



    public function saveModulePermissions(Request $request)
{
    $request->validate([
        'module_id' => 'required|integer|exists:modules,id',
        'role_id' => 'required|integer|exists:roles,id',
        // Validate other fields as necessary
    ]);

    $moduleId = $request->module_id;
    $roleId = $request->role_id;
    $permissions = $request->permissions[$moduleId] ?? []; // Default to an empty array if not set

    // Fetch or create the permission record for the given module and role
    $permissionRecord = Permission::firstOrCreate([
        'module_id' => $moduleId,
        'role_id' => $roleId,
    ]);

    // Update the permissions field; assuming it's stored as JSON in the database
    $permissionRecord->permissions = json_encode($permissions);
    $permissionRecord->save();

    return response()->json([
        'message' => 'Permissions updated successfully.',
        'permissions' => $permissions,
    ]);
}




}
