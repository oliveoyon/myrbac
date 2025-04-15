<?php

namespace App\Http\Controllers;

use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Http\Request;
use App\Services\LogService;

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
        
        // Log the view permissions action
        LogService::logAction('View Permissions', [
            'role_id' => $roleId,
            'role_name' => $role->name,
            'permissions_count' => $permissions->count(),
        ]);
        
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

        // Log the edit permissions action
        LogService::logAction('Edit Permissions', [
            'role_id' => $roleId,
            'role_name' => $role->name,
            'assigned_permissions_count' => $rolePermissions->count(),
        ]);
        
        return response()->json([
            'role' => $role, // Send the role details (for displaying the role name, if needed)
            'rolePermissions' => $rolePermissionsByCategory,  // Send grouped role permissions for checkbox checking
            'allPermissions' => $allPermissions  // Send all permissions for display
        ]);
    }

    // Update permissions for a specific role
    public function updatePermissions(Request $request, $roleId)
    {
        try {
            $role = Role::findOrFail($roleId); // Ensure the role exists

            $permissions = $request->input('permissions'); // Array of permission IDs

            // Sync the permissions with the role
            $role->permissions()->sync($permissions); // Make sure the relationship is correct

            // Log the update permissions action
            LogService::logAction('Update Permissions', [
                'role_id' => $roleId,
                'role_name' => $role->name,
                'updated_permissions' => count($permissions),
            ]);
            
            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            // Log the error if something goes wrong
            Log::error('Error updating permissions for role ' . $roleId . ': ' . $e->getMessage());
            
            return response()->json(['error' => 'Error updating permissions: ' . $e->getMessage()], 500);
        }
    }


}