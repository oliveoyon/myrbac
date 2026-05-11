<?php

use Illuminate\Foundation\Console\ClosureCommand;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

Artisan::command('inspire', function () {
    /** @var ClosureCommand $this */
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Artisan::command('app:safe-clean-database
    {--name=superadmin : Super admin username}
    {--email=superadmin@example.com : Super admin email}
    {--password=ChangeMe@123 : Super admin password}
    {--force : Run without interactive confirmation}', function () {
    /** @var ClosureCommand $this */
    $this->warn('This will delete operational data and all existing users.');
    $this->line('Preserved: categories, districts, pngos, roles, permissions, role_has_permissions, migrations.');
    $this->line('Deleted: users, user role links, direct user permissions, cases, followups, LSID records, file upload records, logs, sessions, cache/jobs.');

    if (! $this->option('force') && ! $this->confirm('Do you have a database backup and want to continue?', false)) {
        $this->info('Cleanup cancelled.');
        return self::SUCCESS;
    }

    $tablesToClean = [
        'file_uploads',
        'todos',
        'follow_up_interventions',
        'lsid_registers',
        'formal_cases',
        'logs',
        'model_has_roles',
        'model_has_permissions',
        'users',
        'sessions',
        'password_reset_tokens',
        'cache',
        'cache_locks',
        'jobs',
        'job_batches',
        'failed_jobs',
    ];

    $driver = DB::getDriverName();

    try {
        if ($driver === 'mysql') {
            DB::statement('SET FOREIGN_KEY_CHECKS=0');
        } elseif ($driver === 'sqlite') {
            DB::statement('PRAGMA foreign_keys = OFF');
        }

        foreach ($tablesToClean as $table) {
            if (Schema::hasTable($table)) {
                DB::table($table)->truncate();
            }
        }

        $role = Role::firstOrCreate([
            'name' => 'Super Admin',
            'guard_name' => 'web',
        ]);

        $user = new User();
        $user->name = $this->option('name');
        $user->email = $this->option('email');
        $user->email_verified_at = now();
        $user->password = Hash::make($this->option('password'));
        $user->status = 2;
        $user->save();

        $user->assignRole($role);
    } finally {
        if ($driver === 'mysql') {
            DB::statement('SET FOREIGN_KEY_CHECKS=1');
        } elseif ($driver === 'sqlite') {
            DB::statement('PRAGMA foreign_keys = ON');
        }
    }

    app(PermissionRegistrar::class)->forgetCachedPermissions();

    $this->info('Database cleanup completed safely.');
    $this->line('Super Admin created:');
    $this->line('Username: '.$this->option('name'));
    $this->line('Email: '.$this->option('email'));
    $this->line('Password: '.$this->option('password'));

    return self::SUCCESS;
})->purpose('Safely clean operational data while preserving roles, permissions, role-permission mappings, categories, districts, and PNGOs.');
