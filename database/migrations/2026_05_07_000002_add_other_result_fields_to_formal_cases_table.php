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
            $table->string('other_result_details')->nullable()->after('release_status_date');
            $table->date('other_result_date')->nullable()->after('other_result_details');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('formal_cases', function (Blueprint $table) {
            $table->dropColumn(['other_result_details', 'other_result_date']);
        });
    }
};
