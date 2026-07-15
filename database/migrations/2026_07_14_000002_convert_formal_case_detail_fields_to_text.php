<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    private array $columns = [
        'guardian_relation_details',
        'lawyer_type_details',
        'legal_representation_details',
        'referral_service_details',
        'source_of_interview_details',
        'special_condition_details',
        'prison_legal_representation_details',
        'other_legal_assistance_details',
        'send_to_details',
        'other_result_details',
        'ministerial_communication_details',
        'convicted_length_details',
        'convicted_sentence_expire_details',
    ];

    public function up(): void
    {
        if (! Schema::hasTable('formal_cases')) {
            return;
        }

        foreach ($this->columns as $column) {
            if (Schema::hasColumn('formal_cases', $column)) {
                DB::statement("ALTER TABLE `formal_cases` MODIFY `{$column}` TEXT NULL");
            }
        }
    }

    public function down(): void
    {
        if (! Schema::hasTable('formal_cases')) {
            return;
        }

        foreach ($this->columns as $column) {
            if (Schema::hasColumn('formal_cases', $column)) {
                DB::statement("ALTER TABLE `formal_cases` MODIFY `{$column}` VARCHAR(125) NULL");
            }
        }
    }
};
