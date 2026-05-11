<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $now = now();
        $categoryName = 'User Management';

        DB::table('categories')->updateOrInsert(
            ['name' => $categoryName],
            ['updated_at' => $now, 'created_at' => $now]
        );

        DB::table('permissions')->updateOrInsert(
            ['name' => 'Change User Password', 'guard_name' => 'web'],
            ['category' => $categoryName, 'updated_at' => $now, 'created_at' => $now]
        );

        $permissionId = DB::table('permissions')
            ->where('name', 'Change User Password')
            ->where('guard_name', 'web')
            ->value('id');

        $roleIds = DB::table('roles')
            ->whereIn('name', ['Admin', 'Super Admin'])
            ->pluck('id');

        foreach ($roleIds as $roleId) {
            DB::table('role_has_permissions')->updateOrInsert([
                'permission_id' => $permissionId,
                'role_id' => $roleId,
            ]);
        }

        app('cache')
            ->store(config('permission.cache.store') !== 'default' ? config('permission.cache.store') : null)
            ->forget(config('permission.cache.key'));
    }

    public function down(): void
    {
        $permissionId = DB::table('permissions')
            ->where('name', 'Change User Password')
            ->where('guard_name', 'web')
            ->value('id');

        if ($permissionId) {
            DB::table('role_has_permissions')->where('permission_id', $permissionId)->delete();
            DB::table('model_has_permissions')->where('permission_id', $permissionId)->delete();
            DB::table('permissions')->where('id', $permissionId)->delete();
        }

        app('cache')
            ->store(config('permission.cache.store') !== 'default' ? config('permission.cache.store') : null)
            ->forget(config('permission.cache.key'));
    }
};
