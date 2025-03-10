<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Role;
use App\Models\Permission;
use Illuminate\Http\Request;

class UserRolePermissionController extends Controller
{
    // Show the form to assign roles and permissions to a user
    public function showAssignForm(User $user)
    {
        $roles = Role::all(); // Get all roles
        $permissions = Permission::all(); // Get all permissions
        return view('dashboard.admin.assign-role-permission', compact('user', 'roles', 'permissions'));
    }

    // Handle form submission for assigning roles and permissions to a user
    public function assignRolePermission(Request $request, User $user)
    {
        // Validate the input
        $request->validate([
            'roles' => 'nullable|array',
            'permissions' => 'nullable|array',
        ]);

        // Assign the selected roles to the user
        if ($request->has('roles')) {
            $user->syncRoles($request->input('roles')); // This will assign the selected roles
        }

        // Assign the selected permissions to the user
        if ($request->has('permissions')) {
            $user->syncPermissions($request->input('permissions')); // This will assign the selected permissions
        }

        // Flash message and redirect
        return redirect()->route('user.assign.role.permission', $user)->with('success', 'Roles and Permissions assigned successfully.');
    }
}