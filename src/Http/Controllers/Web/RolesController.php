<?php

namespace Fortix\Shieldify\Http\Controllers\Web;
use Fortix\Shieldify\Http\Requests\StoreRoleRequest;
use Fortix\Shieldify\Http\Requests\UpdateRoleRequest;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Fortix\Shieldify\Models\Role;
use App\Models\User;

class RolesController extends Controller
{
    public function index()
    {
        $roles = Role::paginate(10); // Fetch roles with pagination

        return view('shieldify::roles.index', compact('roles'));
    }

    public function create()
    {
        return view('shieldify::roles.create');

    }


    public function store(StoreRoleRequest $request)
    {
        $validated = $request->validated();

        Role::create($validated);

        return redirect()->route('roles.index')->with('success', 'Role created successfully.');
    }

    public function show(Role $role)
    {
        return response()->json($role);
    }

    public function edit(Role $role) // Assuming Route Model Binding
    {
        return view('shieldify::roles.edit', compact('role'));
    }


    public function update(UpdateRoleRequest $request, $id)
    {
        $role = Role::findOrFail($id);
        $role->update($request->validated());

        return redirect()->route('roles.index')->with('success', 'Role updated successfully.');
    }

    public function destroy(Role $role)
    {
        $role->delete();
        return response()->json(null, 204);
    }




    public function showAssignUsersForm($roleId)
    {
        $role = Role::findOrFail($roleId);
        $users = User::all(); // Fetch all users

        return view('shieldify::roles.assign', compact('role', 'users'));
    }


    public function assignUsers(Request $request, $roleId)
    {
        $role = Role::findOrFail($roleId);
        $userIds = $request->input('users', []); // Get selected user IDs from the form

        // Sync the role's users with the selected IDs
        $role->users()->sync($userIds);

        return redirect()->route('roles.index')->with('success', 'Users successfully assigned to the role.');
    }



}
