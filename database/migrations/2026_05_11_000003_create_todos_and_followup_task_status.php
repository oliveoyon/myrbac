<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('todos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('district_id')->nullable()->constrained('districts')->nullOnDelete();
            $table->foreignId('pngo_id')->nullable()->constrained('pngos')->nullOnDelete();
            $table->date('task_date');
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('status')->default('pending');
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();
        });

        Schema::table('follow_up_interventions', function (Blueprint $table) {
            $table->string('task_status')->default('pending')->after('to_be_taken_date');
            $table->foreignId('task_completed_by')->nullable()->after('task_status')->constrained('users')->nullOnDelete();
            $table->timestamp('task_completed_at')->nullable()->after('task_completed_by');
        });

        $categoryName = 'ToDo List';
        $now = now();

        if (! DB::table('categories')->where('name', $categoryName)->exists()) {
            DB::table('categories')->insert([
                'name' => $categoryName,
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }

        $permissions = [
            'View ToDo List',
            'Create ToDo Task',
            'Update ToDo Task',
        ];

        foreach ($permissions as $permission) {
            DB::table('permissions')->updateOrInsert(
                ['name' => $permission, 'guard_name' => 'web'],
                ['category' => $categoryName, 'updated_at' => $now, 'created_at' => $now]
            );
        }

        $adminRole = DB::table('roles')
            ->where('name', 'Admin')
            ->orWhere('name', 'Super Admin')
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
            'View ToDo List',
            'Create ToDo Task',
            'Update ToDo Task',
        ];

        $permissionIds = DB::table('permissions')
            ->whereIn('name', $permissions)
            ->where('guard_name', 'web')
            ->pluck('id');

        DB::table('role_has_permissions')->whereIn('permission_id', $permissionIds)->delete();
        DB::table('model_has_permissions')->whereIn('permission_id', $permissionIds)->delete();
        DB::table('permissions')->whereIn('id', $permissionIds)->delete();

        Schema::table('follow_up_interventions', function (Blueprint $table) {
            $table->dropConstrainedForeignId('task_completed_by');
            $table->dropColumn(['task_status', 'task_completed_at']);
        });

        Schema::dropIfExists('todos');

        app('cache')
            ->store(config('permission.cache.store') !== 'default' ? config('permission.cache.store') : null)
            ->forget(config('permission.cache.key'));
    }
};
