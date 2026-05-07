<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('formal_cases', function (Blueprint $table) {
            $table->date('identify_sureties_prison_date')->nullable()->after('identify_sureties_prison_phone');
            $table->string('ministerial_communication_details')->nullable()->after('ministerial_communication');
            $table->string('convicted_length_details')->nullable()->after('convicted_length');
            $table->string('convicted_sentence_expire_details')->nullable()->after('convicted_sentence_expire');
            $table->string('result_of_appeal_details')->nullable()->after('result_of_appeal');
            $table->date('result_of_appeal_date')->nullable()->after('result_of_appeal_details');
        });
    }

    public function down(): void
    {
        Schema::table('formal_cases', function (Blueprint $table) {
            $table->dropColumn([
                'identify_sureties_prison_date',
                'ministerial_communication_details',
                'convicted_length_details',
                'convicted_sentence_expire_details',
                'result_of_appeal_details',
                'result_of_appeal_date',
            ]);
        });
    }
};
