<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $categoryName = 'Formal Case Attachments';
        $permissionName = 'Delete Formal Case Attachment';
        $now = now();

        DB::table('categories')->updateOrInsert(
            ['name' => $categoryName],
            ['created_at' => $now, 'updated_at' => $now]
        );

        DB::table('permissions')->updateOrInsert(
            ['name' => $permissionName, 'guard_name' => 'web'],
            ['category' => $categoryName, 'created_at' => $now, 'updated_at' => $now]
        );

        $permissionId = DB::table('permissions')
            ->where('name', $permissionName)
            ->where('guard_name', 'web')
            ->value('id');

        $roleIds = DB::table('roles')
            ->whereIn('name', ['DPO', 'Admin', 'Super Admin'])
            ->orWhere('id', 1)
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
        $permissionName = 'Delete Formal Case Attachment';
        $permissionId = DB::table('permissions')
            ->where('name', $permissionName)
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
