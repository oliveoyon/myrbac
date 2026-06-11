<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('case_message_threads', function (Blueprint $table) {
            $table->id();
            $table->foreignId('formal_case_id')->constrained('formal_cases')->cascadeOnDelete();
            $table->string('status')->default('open');
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('resolved_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('resolved_at')->nullable();
            $table->timestamps();
        });

        Schema::create('case_messages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('case_message_thread_id')->constrained('case_message_threads')->cascadeOnDelete();
            $table->foreignId('formal_case_id')->constrained('formal_cases')->cascadeOnDelete();
            $table->foreignId('sender_id')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('receiver_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('receiver_role')->nullable();
            $table->text('message');
            $table->timestamp('read_at')->nullable();
            $table->timestamps();
        });

        $categoryName = 'Case Communication';
        $now = now();

        DB::table('categories')->updateOrInsert(
            ['name' => $categoryName],
            ['updated_at' => $now, 'created_at' => $now]
        );

        $permissions = [
            'View Case Messages',
            'Send Case Message',
            'Reply Case Message',
            'Mark Case Message Read',
            'Resolve Case Message',
            'View All Case Messages',
        ];

        foreach ($permissions as $permission) {
            DB::table('permissions')->updateOrInsert(
                ['name' => $permission, 'guard_name' => 'web'],
                ['category' => $categoryName, 'updated_at' => $now, 'created_at' => $now]
            );
        }

        $adminRoles = DB::table('roles')
            ->whereIn('name', ['Admin', 'Super Admin'])
            ->orWhere('id', 1)
            ->get();

        if ($adminRoles->isNotEmpty()) {
            $permissionIds = DB::table('permissions')
                ->whereIn('name', $permissions)
                ->where('guard_name', 'web')
                ->pluck('id');

            foreach ($adminRoles as $role) {
                foreach ($permissionIds as $permissionId) {
                    DB::table('role_has_permissions')->updateOrInsert([
                        'permission_id' => $permissionId,
                        'role_id' => $role->id,
                    ]);
                }
            }
        }

        app('cache')
            ->store(config('permission.cache.store') !== 'default' ? config('permission.cache.store') : null)
            ->forget(config('permission.cache.key'));
    }

    public function down(): void
    {
        $permissions = [
            'View Case Messages',
            'Send Case Message',
            'Reply Case Message',
            'Mark Case Message Read',
            'Resolve Case Message',
            'View All Case Messages',
        ];

        $permissionIds = DB::table('permissions')
            ->whereIn('name', $permissions)
            ->where('guard_name', 'web')
            ->pluck('id');

        DB::table('role_has_permissions')->whereIn('permission_id', $permissionIds)->delete();
        DB::table('model_has_permissions')->whereIn('permission_id', $permissionIds)->delete();
        DB::table('permissions')->whereIn('id', $permissionIds)->delete();

        Schema::dropIfExists('case_messages');
        Schema::dropIfExists('case_message_threads');

        app('cache')
            ->store(config('permission.cache.store') !== 'default' ? config('permission.cache.store') : null)
            ->forget(config('permission.cache.key'));
    }
};
