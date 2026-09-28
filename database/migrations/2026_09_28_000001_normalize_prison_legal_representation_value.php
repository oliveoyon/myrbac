<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::table('formal_cases')
            ->where('prison_legal_representation', 'District Legal Aid Offic')
            ->update(['prison_legal_representation' => 'District Legal Aid Office']);
    }

    public function down(): void
    {
        // The corrected canonical value must not be reverted to the legacy typo.
    }
};
