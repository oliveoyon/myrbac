<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    private const CATEGORY = 'Endorsed Result Documents';

    private const PERMISSIONS = [
        'View Endorsed Result Documents',
        'Upload Endorsed Result Document',
        'Download Endorsed Result Document',
        'Replace Endorsed Result Document',
        'Delete Endorsed Result Document',
    ];

    public function up(): void
    {
        $now = now();

        DB::table('categories')->updateOrInsert(
            ['name' => self::CATEGORY],
            ['created_at' => $now, 'updated_at' => $now]
        );

        foreach (self::PERMISSIONS as $permission) {
            DB::table('permissions')->updateOrInsert(
                ['name' => $permission, 'guard_name' => 'web'],
                ['category' => self::CATEGORY, 'created_at' => $now, 'updated_at' => $now]
            );
        }

        app('cache')
            ->store(config('permission.cache.store') !== 'default' ? config('permission.cache.store') : null)
            ->forget(config('permission.cache.key'));
    }

    public function down(): void
    {
        $permissionIds = DB::table('permissions')
            ->where('guard_name', 'web')
            ->whereIn('name', self::PERMISSIONS)
            ->pluck('id');

        if ($permissionIds->isNotEmpty()) {
            DB::table('role_has_permissions')->whereIn('permission_id', $permissionIds)->delete();
            DB::table('model_has_permissions')->whereIn('permission_id', $permissionIds)->delete();
            DB::table('permissions')->whereIn('id', $permissionIds)->delete();
        }

        if (! DB::table('permissions')->where('category', self::CATEGORY)->exists()) {
            DB::table('categories')->where('name', self::CATEGORY)->delete();
        }

        app('cache')
            ->store(config('permission.cache.store') !== 'default' ? config('permission.cache.store') : null)
            ->forget(config('permission.cache.key'));
    }
};
