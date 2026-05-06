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
            $table->date('identify_sureties_date')->nullable()->after('identify_sureties');
            $table->string('referral_service_details')->nullable()->after('referral_service');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('formal_cases', function (Blueprint $table) {
            $table->dropColumn(['identify_sureties_date', 'referral_service_details']);
        });
    }
};
