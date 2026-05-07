<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('formal_cases', function (Blueprint $table) {
            $table->date('case_resolved_date')->nullable()->after('referral_service_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('formal_cases', function (Blueprint $table) {
            $table->dropColumn('case_resolved_date');
        });
    }
};
