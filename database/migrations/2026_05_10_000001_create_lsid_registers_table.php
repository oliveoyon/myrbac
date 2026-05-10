<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('lsid_registers', function (Blueprint $table) {
            $table->id();
            $table->date('service_date');
            $table->string('receiver_name');
            $table->string('mobile_number')->nullable();
            $table->string('sex');
            $table->json('other_information')->nullable();
            $table->json('receiver_types')->nullable();
            $table->json('interventions_taken')->nullable();
            $table->json('service_types')->nullable();
            $table->string('receiver_type_other')->nullable();
            $table->string('service_type_other')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });

        $categoryName = 'LSID Register';
        $now = now();

        if (! DB::table('categories')->where('name', $categoryName)->exists()) {
            DB::table('categories')->insert([
                'name' => $categoryName,
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }

        $permissions = [
            'View LSID Register',
            'Create LSID Register',
            'Edit LSID Register',
            'Delete LSID Register',
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
            'View LSID Register',
            'Create LSID Register',
            'Edit LSID Register',
            'Delete LSID Register',
        ];

        $permissionIds = DB::table('permissions')
            ->whereIn('name', $permissions)
            ->where('guard_name', 'web')
            ->pluck('id');

        DB::table('role_has_permissions')->whereIn('permission_id', $permissionIds)->delete();
        DB::table('model_has_permissions')->whereIn('permission_id', $permissionIds)->delete();
        DB::table('permissions')->whereIn('id', $permissionIds)->delete();

        Schema::dropIfExists('lsid_registers');

        app('cache')
            ->store(config('permission.cache.store') !== 'default' ? config('permission.cache.store') : null)
            ->forget(config('permission.cache.key'));
    }
};
