<?php

namespace Fortix\Shieldify\Http\Controllers\Api;

use Illuminate\Http\Request;
use Fortix\Shieldify\Models\Permission;
use Fortix\Shieldify\Models\Module;
use Fortix\Shieldify\Http\Requests\StorePermissionRequest;
use Fortix\Shieldify\Http\Requests\UpdatePermissionRequest;
use Fortix\Shieldify\Http\Resources\PermissionResource;
use Fortix\Shieldify\Http\Resources\PermissionCollection;
use Fortix\Shieldify\Http\Resources\ModuleResource;
use App\Http\Controllers\Controller;

class PermissionsApiController extends Controller
{
    public function index()
    {
        $permissions = Permission::with(['role', 'module'])->paginate(10);
        return new PermissionCollection($permissions);
    }

    public function store(StorePermissionRequest $request)
    {
        $permission = Permission::create($request->validated());
        return new PermissionResource($permission);
    }

    public function show(Permission $permission)
    {
        return new PermissionResource($permission);
    }

    public function update(UpdatePermissionRequest $request, Permission $permission)
    {
        $permission->update($request->validated());
        return new PermissionResource($permission);
    }

    public function destroy(Permission $permission)
    {
        $permission->delete();
        return response()->json(['message' => 'Permission successfully deleted']);
    }

    public function getPermissionsForRole(Request $request, $roleId)
    {
        $modules = Module::with(['permissions' => function ($query) use ($roleId) {
            $query->where('role_id', $roleId);
        }])->get();

        return response()->json(['modules' => ModuleResource::collection($modules)]);
    }

    public function saveModulePermissions(Request $request)
    {
        $validated = $request->validate([
            'module_id' => 'required|integer|exists:modules,id',
            'role_id' => 'required|integer|exists:roles,id',
            'permissions' => 'required|array',
        ]);

        $permissionRecord = Permission::updateOrCreate(
            ['module_id' => $validated['module_id'], 'role_id' => $validated['role_id']],
            ['permissions' => json_encode($validated['permissions'])]
        );

        return new PermissionResource($permissionRecord);
    }
}
