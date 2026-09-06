<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('app_settings', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->text('value')->nullable();
            $table->string('type')->default('string');
            $table->string('label');
            $table->text('description')->nullable();
            $table->string('group')->nullable();
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });

        $now = now();
        $settings = [
            [
                'key' => 'case_entry_interview_date_cutoff_day',
                'value' => (string) config('a2j.case_entry.interview_date_cutoff_day', 5),
                'type' => 'integer',
                'label' => 'Case Entry Cutoff Day',
                'description' => 'Previous month interview data can be entered until this day of the next month.',
                'group' => 'Case Entry',
            ],
            [
                'key' => 'formal_case_upload_max_files',
                'value' => (string) config('a2j.formal_case_upload.max_files', 20),
                'type' => 'integer',
                'label' => 'Maximum Attachment Files',
                'description' => 'Maximum number of files allowed with a formal case submission.',
                'group' => 'Attachments',
            ],
            [
                'key' => 'formal_case_upload_max_size_mb',
                'value' => (string) config('a2j.formal_case_upload.max_size_mb', 2),
                'type' => 'integer',
                'label' => 'Maximum Attachment Size',
                'description' => 'Maximum size for each attachment file in MB.',
                'group' => 'Attachments',
            ],
            [
                'key' => 'report_header_title',
                'value' => (string) config('a2j.reporting.header_title', 'Access to Justice for Women'),
                'type' => 'string',
                'label' => 'Report Header Title',
                'description' => 'Primary title used in official report headers.',
                'group' => 'Reports',
            ],
            [
                'key' => 'report_header_subtitle',
                'value' => (string) config('a2j.reporting.header_subtitle', ''),
                'type' => 'string',
                'label' => 'Report Header Subtitle',
                'description' => 'Subtitle or implementation line used in official report headers.',
                'group' => 'Reports',
            ],
        ];

        foreach ($settings as $setting) {
            DB::table('app_settings')->insert(array_merge($setting, [
                'created_at' => $now,
                'updated_at' => $now,
            ]));
        }

        DB::table('categories')->updateOrInsert(
            ['name' => 'General Settings'],
            ['created_at' => $now, 'updated_at' => $now]
        );

        $permissionNames = ['View System Settings', 'Update System Settings'];

        foreach ($permissionNames as $permissionName) {
            DB::table('permissions')->updateOrInsert(
                ['name' => $permissionName, 'guard_name' => 'web'],
                ['category' => 'General Settings', 'created_at' => $now, 'updated_at' => $now]
            );
        }

        $permissionIds = DB::table('permissions')
            ->whereIn('name', $permissionNames)
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
        $permissionIds = DB::table('permissions')
            ->whereIn('name', ['View System Settings', 'Update System Settings'])
            ->where('guard_name', 'web')
            ->pluck('id');

        if ($permissionIds->isNotEmpty()) {
            DB::table('role_has_permissions')->whereIn('permission_id', $permissionIds)->delete();
            DB::table('model_has_permissions')->whereIn('permission_id', $permissionIds)->delete();
            DB::table('permissions')->whereIn('id', $permissionIds)->delete();
        }

        Schema::dropIfExists('app_settings');

        app('cache')
            ->store(config('permission.cache.store') !== 'default' ? config('permission.cache.store') : null)
            ->forget(config('permission.cache.key'));
    }
};
