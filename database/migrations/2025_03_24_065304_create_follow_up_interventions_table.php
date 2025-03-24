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
        Schema::create('follow_up_interventions', function (Blueprint $table) {
            $table->id();
            $table->string('central_id'); // Foreign key reference
            $table->unsignedBigInteger('user_id'); // Foreign key reference

            $table->text('intervention_taken')->nullable();
            $table->date('intervention_taken_date')->nullable();

            $table->text('intervention_to_be_taken')->nullable();
            $table->date('to_be_taken_date')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('follow_up_interventions');
    }
};
