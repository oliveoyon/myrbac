<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('formal_cases', function (Blueprint $table) {
            $table->dropColumn('result_of_appeal_details');
            $table->date('prison_case_resolved_date')->nullable()->after('result_of_appeal_date');
        });
    }

    public function down(): void
    {
        Schema::table('formal_cases', function (Blueprint $table) {
            $table->string('result_of_appeal_details')->nullable()->after('result_of_appeal');
            $table->dropColumn('prison_case_resolved_date');
        });
    }
};
