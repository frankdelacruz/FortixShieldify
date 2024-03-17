<?php

namespace Fortix\Shieldify\Http\Controllers\Api;
use Fortix\Shieldify\Http\Requests\StoreRoleRequest;
use Fortix\Shieldify\Http\Requests\UpdateRoleRequest;
use Fortix\Shieldify\Http\Requests\AssignUsersRequest;
use Fortix\Shieldify\Http\Resources\RoleResource;
use Fortix\Shieldify\Http\Resources\UserResource;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Fortix\Shieldify\Models\Role;
use App\Models\User;

class RolesApiController extends Controller
{
    public function index()
    {
        $roles = Role::all(); // Or any query you need
        return RoleResource::collection($roles);
    }

    public function create()
    {
        return view('shieldify::roles.create');

    }


   // Store a new role
   public function store(StoreRoleRequest $request)
   {
       $role = Role::create($request->validated());
       return new RoleResource($role);
   }

    public function show(Role $role)
    {
        return new RoleResource($role);
    }



    public function update(UpdateRoleRequest $request, $id)
    {
        $role = Role::findOrFail($id); // Ensure the role exists or return 404
        $role->update($request->validated()); // Validate and update the role

        return new RoleResource($role); // Return the updated role as an API resource
    }


    // Delete a role
    public function destroy(Role $role)
    {
        $role->delete();
        return response()->json(null, 204); // No content to return
    }




    public function showAssignableUsers($roleId)
    {
        $role = Role::findOrFail($roleId);
        $allUsers = User::all();
        $assignedUserIds = $role->users->pluck('id')->toArray();

        return response()->json([
            'role' => new RoleResource($role),
            'users' => UserResource::collection($allUsers),
            'assignedUserIds' => $assignedUserIds,
        ]);
    }



    public function assignUsersToRole(AssignUsersRequest $request, $roleId)
    {
        $role = Role::findOrFail($roleId);
        $userIds = $request->input('userIds'); // The 'userIds' input is now guaranteed to be an array of existing user IDs

        // Sync the role's users with the incoming user IDs
        $role->users()->sync($userIds);

        return response()->json([
            'message' => 'Users successfully assigned to the role.',
            'assignedUsers' => UserResource::collection($role->users),
        ]);
    }



}
