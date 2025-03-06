<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Role; // Import Spatie Role Model

class RoleController extends Controller
{
    public function roles()
    {
        $roles = Role::all();
        return view('dashboard.admin.role', compact('roles'));
    }

    // Function to Add a New Role
    public function roleAdd(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:roles,name',
        ]);

        // Create a new role using Spatie
        $role = Role::create(['name' => $request->name]);

        return response()->json([
            'success' => true,
            'message' => 'Role added successfully!',
            'role' => $role,
        ]);
    }

    // Function to Update an Existing Role
    public function roleUpdate(Request $request, $roleId)
    {
        $request->validate([
            'name' => 'required|unique:roles,name,' . $roleId,
        ]);

        $role = Role::findOrFail($roleId);
        $role->name = $request->name;
        $role->save();

        return response()->json([
            'success' => true,
            'message' => 'Role updated successfully!',
            'role' => $role,
        ]);
    }

    // Function to Delete a Role
    public function roleDelete($roleId)
    {
        $role = Role::findOrFail($roleId);
        $role->delete();

        return response()->json([
            'success' => true,
            'message' => 'Role deleted successfully!',
        ]);
    }
}
