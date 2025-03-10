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
    
    // Group permissions by category
    $groupedPermissions = $permissions->groupBy('category');
    
    return response()->json(['permissions' => $groupedPermissions]);
}

    // Edit permissions for a specific role
public function editPermissions($roleId)
{
    $role = Role::findOrFail($roleId);
    
    // Get all permissions with their categories
    $allPermissions = Permission::all();  
    
    // Get the permissions assigned to the role
    $rolePermissions = $role->permissions; // We use the full permission objects here to access categories, not just the IDs
    
    // Group the permissions assigned to the role by category for easier rendering
    $rolePermissionsByCategory = $rolePermissions->groupBy('category');

    return response()->json([
        'role' => $role, // Send the role details (for displaying the role name, if needed)
        'rolePermissions' => $rolePermissionsByCategory,  // Send grouped role permissions for checkbox checking
        'allPermissions' => $allPermissions  // Send all permissions for display
    ]);
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