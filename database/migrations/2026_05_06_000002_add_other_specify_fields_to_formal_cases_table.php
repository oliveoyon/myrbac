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
            $table->string('guardian_relation_details')->nullable()->after('guardian_relation');
            $table->string('lawyer_type_details')->nullable()->after('lawyer_type');
            $table->string('legal_representation_details')->nullable()->after('legal_representation');
            $table->string('source_of_interview_details')->nullable()->after('source_of_interview');
            $table->string('special_condition_details')->nullable()->after('special_condition');
            $table->string('prison_legal_representation_details')->nullable()->after('prison_legal_representation');
            $table->string('other_legal_assistance_details')->nullable()->after('other_legal_assistance');
            $table->string('send_to_details')->nullable()->after('send_to');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('formal_cases', function (Blueprint $table) {
            $table->dropColumn([
                'guardian_relation_details',
                'lawyer_type_details',
                'legal_representation_details',
                'source_of_interview_details',
                'special_condition_details',
                'prison_legal_representation_details',
                'other_legal_assistance_details',
                'send_to_details',
            ]);
        });
    }
};
