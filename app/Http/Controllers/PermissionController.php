<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
use App\Models\Category;
use App\Services\LogService;

class PermissionController extends Controller
{
    public function index()
    {
        $permissions = Permission::all();
        $categories = Category::all();
        return view('dashboard.admin.permission', compact('permissions', 'categories'));
    }

    // Function to Store a New Permission
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:permissions,name',
            'category' => 'required', // Ensure category is selected
        ]);

        // Create a new permission
        $permission = Permission::create([
            'name' => $request->name,
            'guard_name' => 'web', // Adjust this if needed
            'category' => $request->category, // Store the category
        ]);

        // Log the action
        LogService::logAction('Permission Create', [
            'permission_id' => $permission->id,
            'permission_name' => $permission->name,
            'category' => $permission->category,
        ]);

        return response()->json([
            'success' => true,
            'id' => $permission->id,
            'name' => $permission->name,
            'category' => $permission->category,
        ]);
    }

    // Function to Update an Existing Permission
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|unique:permissions,name,' . $id,
            'category' => 'required',
        ]);

        $permission = Permission::findOrFail($id);
        $oldName = $permission->name;
        $oldCategory = $permission->category;

        $permission->update([
            'name' => $request->name,
            'category' => $request->category,
        ]);

        // Log the update action
        LogService::logAction('Permission Update', [
            'permission_id' => $id,
            'changed_fields' => "Permission name changed from '{$oldName}' to '{$permission->name}', Category changed from '{$oldCategory}' to '{$permission->category}'",
        ]);

        return response()->json([
            'success' => true,
            'id' => $permission->id,
            'name' => $permission->name,
            'category' => $permission->category,
        ]);
    }

    // Function to Delete a Permission
    public function destroy($id)
    {
        $permission = Permission::findOrFail($id);
        $permissionName = $permission->name;
        $permissionCategory = $permission->category;

        $permission->delete();

        // Log the delete action
        LogService::logAction('Permission Delete', [
            'permission_id' => $id,
            'deleted_name' => $permissionName,
            'deleted_category' => $permissionCategory,
        ]);

        return response()->json(['success' => true]);
    }

}
