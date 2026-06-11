<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasColumn('formal_cases', 'deleted_at')) {
            Schema::table('formal_cases', function (Blueprint $table) {
                $table->softDeletes()->after('updated_at');
            });
        }

        $categoryName = 'Formal Cases';
        $permissionNames = [
            'Delete Formal Case',
            'Restore Formal Case',
            'View Deleted Formal Cases',
        ];
        $now = now();

        DB::table('categories')->updateOrInsert(
            ['name' => $categoryName],
            ['created_at' => $now, 'updated_at' => $now]
        );

        foreach ($permissionNames as $permissionName) {
            DB::table('permissions')->updateOrInsert(
                ['name' => $permissionName, 'guard_name' => 'web'],
                ['category' => $categoryName, 'created_at' => $now, 'updated_at' => $now]
            );
        }

        $deletePermissionId = DB::table('permissions')
            ->where('name', 'Delete Formal Case')
            ->where('guard_name', 'web')
            ->value('id');

        $restorePermissionId = DB::table('permissions')
            ->where('name', 'Restore Formal Case')
            ->where('guard_name', 'web')
            ->value('id');

        $viewDeletedPermissionId = DB::table('permissions')
            ->where('name', 'View Deleted Formal Cases')
            ->where('guard_name', 'web')
            ->value('id');

        $deleteRoleIds = DB::table('roles')
            ->whereIn('name', ['DPO', 'Admin', 'Super Admin'])
            ->orWhere('id', 1)
            ->pluck('id');

        $adminRoleIds = DB::table('roles')
            ->whereIn('name', ['Admin', 'Super Admin'])
            ->orWhere('id', 1)
            ->pluck('id');

        foreach ($deleteRoleIds as $roleId) {
            DB::table('role_has_permissions')->updateOrInsert([
                'permission_id' => $deletePermissionId,
                'role_id' => $roleId,
            ]);
        }

        foreach ($adminRoleIds as $roleId) {
            foreach ([$restorePermissionId, $viewDeletedPermissionId] as $permissionId) {
                DB::table('role_has_permissions')->updateOrInsert([
                    'permission_id' => $permissionId,
                    'role_id' => $roleId,
                ]);
            }
        }

        app('cache')
            ->store(config('permission.cache.store') !== 'default' ? config('permission.cache.store') : null)
            ->forget(config('permission.cache.key'));
    }

    public function down(): void
    {
        $permissionNames = [
            'Delete Formal Case',
            'Restore Formal Case',
            'View Deleted Formal Cases',
        ];

        $permissionIds = DB::table('permissions')
            ->whereIn('name', $permissionNames)
            ->where('guard_name', 'web')
            ->pluck('id');

        if ($permissionIds->isNotEmpty()) {
            DB::table('role_has_permissions')->whereIn('permission_id', $permissionIds)->delete();
            DB::table('model_has_permissions')->whereIn('permission_id', $permissionIds)->delete();
            DB::table('permissions')->whereIn('id', $permissionIds)->delete();
        }

        if (Schema::hasColumn('formal_cases', 'deleted_at')) {
            Schema::table('formal_cases', function (Blueprint $table) {
                $table->dropSoftDeletes();
            });
        }

        app('cache')
            ->store(config('permission.cache.store') !== 'default' ? config('permission.cache.store') : null)
            ->forget(config('permission.cache.key'));
    }
};
