<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class CategoriesAndPermissionsSeeder extends Seeder
{
    public function run()
    {
        // Seed Categories Table
        $categories = [
            ['name' => 'Dashboard'],
            ['name' => 'Category Management'],
            ['name' => 'District Management'],
            ['name' => 'PNGO Management'],
            ['name' => 'User Management'],
            ['name' => 'Role Management'],
            ['name' => 'Permission Management'],
            ['name' => 'Role Permission Management'],
        ];
        
        // Insert categories into the categories table
        foreach ($categories as $category) {
            DB::table('categories')->insert($category);
        }
        
        $permissions = [
            ['name' => 'Dashboard', 'guard_name' => 'web', 'category' => 'Dashboard'],
            ['name' => 'View Categories', 'guard_name' => 'web', 'category' => 'Category Management'],
            ['name' => 'Add Category', 'guard_name' => 'web', 'category' => 'Category Management'],
            ['name' => 'Edit Category', 'guard_name' => 'web', 'category' => 'Category Management'],
            ['name' => 'Delete Category', 'guard_name' => 'web', 'category' => 'Category Management'],
        
            ['name' => 'View Districts', 'guard_name' => 'web', 'category' => 'District Management'],
            ['name' => 'Add District', 'guard_name' => 'web', 'category' => 'District Management'],
            ['name' => 'Edit District', 'guard_name' => 'web', 'category' => 'District Management'],
            ['name' => 'Delete District', 'guard_name' => 'web', 'category' => 'District Management'],
        
            ['name' => 'View PNGOs', 'guard_name' => 'web', 'category' => 'PNGO Management'],
            ['name' => 'Add PNGO', 'guard_name' => 'web', 'category' => 'PNGO Management'],
            ['name' => 'Edit PNGO', 'guard_name' => 'web', 'category' => 'PNGO Management'],
            ['name' => 'Delete PNGO', 'guard_name' => 'web', 'category' => 'PNGO Management'],
        
            ['name' => 'View Users', 'guard_name' => 'web', 'category' => 'User Management'],
            ['name' => 'Add User', 'guard_name' => 'web', 'category' => 'User Management'],
            ['name' => 'View User Details', 'guard_name' => 'web', 'category' => 'User Management'],
            ['name' => 'Edit User', 'guard_name' => 'web', 'category' => 'User Management'],
            ['name' => 'View User Permissions', 'guard_name' => 'web', 'category' => 'User Management'],
            ['name' => 'Edit User Permissions', 'guard_name' => 'web', 'category' => 'User Management'],
            ['name' => 'Update User Permissions', 'guard_name' => 'web', 'category' => 'User Management'],
        
            ['name' => 'View Roles', 'guard_name' => 'web', 'category' => 'Role Management'],
            ['name' => 'Add Role', 'guard_name' => 'web', 'category' => 'Role Management'],
            ['name' => 'Edit Role', 'guard_name' => 'web', 'category' => 'Role Management'],
            ['name' => 'Delete Role', 'guard_name' => 'web', 'category' => 'Role Management'],
        
            ['name' => 'View Permissions', 'guard_name' => 'web', 'category' => 'Permission Management'],
            ['name' => 'Add Permission', 'guard_name' => 'web', 'category' => 'Permission Management'],
            ['name' => 'Edit Permission', 'guard_name' => 'web', 'category' => 'Permission Management'],
            ['name' => 'Delete Permission', 'guard_name' => 'web', 'category' => 'Permission Management'],
        
            ['name' => 'Manage Role Permissions', 'guard_name' => 'web', 'category' => 'Role Permission Management'],
            ['name' => 'View Role Permissions', 'guard_name' => 'web', 'category' => 'Role Permission Management'],
            ['name' => 'Edit Role Permissions', 'guard_name' => 'web', 'category' => 'Role Permission Management'],
            ['name' => 'Update Role Permissions', 'guard_name' => 'web', 'category' => 'Role Permission Management'],
        ];
        
        // Insert permissions into the permissions table
        foreach ($permissions as $permission) {
            DB::table('permissions')->insert($permission);
        }

        // Assign Permissions to Role
        $adminRole = Role::find(1); // Ensure the Admin role exists
        if ($adminRole) {
            $allPermissions = Permission::pluck('id')->toArray();
            $adminRole->permissions()->sync($allPermissions);
        }
    }
}
