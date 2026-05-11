<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pngos', function (Blueprint $table) {
            $table->unique(['district_id', 'name'], 'pngos_district_id_name_unique');
        });
    }

    public function down(): void
    {
        Schema::table('pngos', function (Blueprint $table) {
            $table->dropUnique('pngos_district_id_name_unique');
        });
    }
};
