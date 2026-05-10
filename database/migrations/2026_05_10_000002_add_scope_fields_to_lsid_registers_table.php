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
            $table->foreignId('district_id')->nullable()->after('id')->constrained('districts')->nullOnDelete();
            $table->foreignId('pngo_id')->nullable()->after('district_id')->constrained('pngos')->nullOnDelete();
        });

        $categoryName = 'LSID Register';
        $now = now();
        $permissions = [
            'View LSID Management',
            'View LSID Report',
            'Generate LSID Report',
        ];

        foreach ($permissions as $permission) {
            DB::table('permissions')->updateOrInsert(
                ['name' => $permission, 'guard_name' => 'web'],
                ['category' => $categoryName, 'updated_at' => $now, 'created_at' => $now]
            );
        }

        $adminRole = DB::table('roles')
            ->where('name', 'Admin')
            ->orWhere('id', 1)
            ->orderBy('id')
            ->first();

        if ($adminRole) {
            $permissionIds = DB::table('permissions')
                ->whereIn('name', $permissions)
                ->where('guard_name', 'web')
                ->pluck('id');

            foreach ($permissionIds as $permissionId) {
                DB::table('role_has_permissions')->updateOrInsert([
                    'permission_id' => $permissionId,
                    'role_id' => $adminRole->id,
                ]);
            }
        }

        app('cache')
            ->store(config('permission.cache.store') !== 'default' ? config('permission.cache.store') : null)
            ->forget(config('permission.cache.key'));
    }

    public function down(): void
    {
        $permissions = [
            'View LSID Management',
            'View LSID Report',
            'Generate LSID Report',
        ];

        $permissionIds = DB::table('permissions')
            ->whereIn('name', $permissions)
            ->where('guard_name', 'web')
            ->pluck('id');

        DB::table('role_has_permissions')->whereIn('permission_id', $permissionIds)->delete();
        DB::table('model_has_permissions')->whereIn('permission_id', $permissionIds)->delete();
        DB::table('permissions')->whereIn('id', $permissionIds)->delete();

        Schema::table('lsid_registers', function (Blueprint $table) {
            $table->dropConstrainedForeignId('pngo_id');
            $table->dropConstrainedForeignId('district_id');
        });

        app('cache')
            ->store(config('permission.cache.store') !== 'default' ? config('permission.cache.store') : null)
            ->forget(config('permission.cache.key'));
    }
};
