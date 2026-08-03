<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('lsid_registers', function (Blueprint $table) {
            if (! Schema::hasColumn('lsid_registers', 'lsid_id')) {
                $table->string('lsid_id')->nullable()->unique()->after('id');
            }

            if (! Schema::hasColumn('lsid_registers', 'remarks')) {
                $table->text('remarks')->nullable()->after('service_type_other');
            }
        });

        DB::statement('ALTER TABLE lsid_registers MODIFY receiver_name VARCHAR(255) NULL');

        $now = now();
        $categoryName = 'LSID Register';
        $permissions = [
            'View LSID Import Page',
            'Import LSID Registers',
        ];

        DB::table('categories')->updateOrInsert(
            ['name' => $categoryName],
            ['created_at' => $now, 'updated_at' => $now]
        );

        foreach ($permissions as $permission) {
            DB::table('permissions')->updateOrInsert(
                ['name' => $permission, 'guard_name' => 'web'],
                ['category' => $categoryName, 'created_at' => $now, 'updated_at' => $now]
            );
        }

        $permissionIds = DB::table('permissions')
            ->whereIn('name', $permissions)
            ->where('guard_name', 'web')
            ->pluck('id');

        $roleIds = DB::table('roles')
            ->whereIn('name', ['Admin', 'Super Admin'])
            ->orWhere('id', 1)
            ->pluck('id');

        foreach ($roleIds as $roleId) {
            foreach ($permissionIds as $permissionId) {
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
        DB::statement('ALTER TABLE lsid_registers MODIFY receiver_name VARCHAR(255) NOT NULL');

        Schema::table('lsid_registers', function (Blueprint $table) {
            if (Schema::hasColumn('lsid_registers', 'remarks')) {
                $table->dropColumn('remarks');
            }

            if (Schema::hasColumn('lsid_registers', 'lsid_id')) {
                $table->dropUnique(['lsid_id']);
                $table->dropColumn('lsid_id');
            }
        });

        $permissionIds = DB::table('permissions')
            ->whereIn('name', ['View LSID Import Page', 'Import LSID Registers'])
            ->where('guard_name', 'web')
            ->pluck('id');

        if ($permissionIds->isNotEmpty()) {
            DB::table('role_has_permissions')->whereIn('permission_id', $permissionIds)->delete();
            DB::table('model_has_permissions')->whereIn('permission_id', $permissionIds)->delete();
            DB::table('permissions')->whereIn('id', $permissionIds)->delete();
        }

        app('cache')
            ->store(config('permission.cache.store') !== 'default' ? config('permission.cache.store') : null)
            ->forget(config('permission.cache.key'));
    }
};
