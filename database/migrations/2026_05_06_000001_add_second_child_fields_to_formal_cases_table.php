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
            $table->string('child_2_sex')->nullable()->after('child_age');
            $table->integer('child_2_age')->nullable()->after('child_2_sex');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('formal_cases', function (Blueprint $table) {
            $table->dropColumn(['child_2_sex', 'child_2_age']);
        });
    }
};
