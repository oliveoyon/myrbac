<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
use App\Models\Category;

class PermissionController extends Controller
{
    public function index()
    {
        $permissions = Permission::all();
        $categories = Category::all();
        return view('dashboard.admin.permission', compact('permissions', 'categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:permissions,name',
            'category' => 'required', // Ensure category is selected
        ]);

        $permission = Permission::create([
            'name' => $request->name,
            'guard_name' => 'web', // Adjust this if needed
            'category' => $request->category, // Store the category
        ]);

        return response()->json(['success' => true, 'id' => $permission->id, 'name' => $permission->name, 'category' => $permission->category]);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|unique:permissions,name,' . $id,
            'category' => 'required',
        ]);

        $permission = Permission::findOrFail($id);
        $permission->update([
            'name' => $request->name,
            'category' => $request->category,
        ]);

        return response()->json(['success' => true, 'id' => $permission->id, 'name' => $permission->name, 'category' => $permission->category]);
    }

    public function destroy($id)
    {
        Permission::findOrFail($id)->delete();
        return response()->json(['success' => true]);
    }
}
