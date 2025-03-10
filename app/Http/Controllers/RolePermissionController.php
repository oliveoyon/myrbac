<?php

namespace App\Http\Controllers;

use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Http\Request;

class RolePermissionController extends Controller
{
    // Show the page with all roles and permissions
    public function index()
    {
        $roles = Role::all();
        return view('dashboard.admin.role_permissions', compact('roles'));
    }

    // View permissions for a specific role
    public function viewPermissions($roleId)
    {
        $role = Role::findOrFail($roleId);
        $permissions = $role->permissions;  // Get associated permissions
        return response()->json(['permissions' => $permissions]);
    }

    // Edit permissions for a specific role
    public function editPermissions($roleId)
    {
        $role = Role::findOrFail($roleId);
        $allPermissions = Permission::all();  // Get all permissions
        $rolePermissions = $role->permissions->pluck('id')->toArray();  // Get assigned permissions for the role
        return response()->json(['rolePermissions' => $rolePermissions, 'allPermissions' => $allPermissions]);
    }

    // Inside your RoleController.php
public function updatePermissions(Request $request, Role $role)
{
    // Validate the incoming request
    $request->validate([
        'permissions' => 'required|array',
    ]);

    // Sync the permissions with the given role
    $role->permissions()->sync($request->permissions);

    // Return a response
    return response()->json(['message' => 'Permissions updated successfully.']);
}

}