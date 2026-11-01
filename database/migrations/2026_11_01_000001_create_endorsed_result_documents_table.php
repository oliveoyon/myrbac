<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('endorsed_result_documents', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->id();
            $table->unsignedBigInteger('district_id')->index();
            $table->unsignedBigInteger('pngo_id')->index();
            $table->date('reporting_month');
            $table->string('document_title', 255);
            $table->string('original_file_name', 255);
            $table->string('stored_file_name', 255);
            $table->string('file_path', 500);
            $table->string('mime_type', 100);
            $table->unsignedBigInteger('file_size');
            $table->unsignedBigInteger('uploaded_by')->index();
            $table->unsignedBigInteger('updated_by')->nullable()->index();
            $table->timestamps();

            $table->unique(['district_id', 'reporting_month'], 'endorsed_documents_district_month_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('endorsed_result_documents');
    }
};
