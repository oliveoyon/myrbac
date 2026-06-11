<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('case_message_threads')) {
            Schema::create('case_message_threads', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('formal_case_id')->index();
                $table->string('status')->default('open');
                $table->unsignedBigInteger('created_by')->nullable()->index();
                $table->unsignedBigInteger('resolved_by')->nullable()->index();
                $table->timestamp('resolved_at')->nullable();
                $table->timestamps();
            });
        }

        if (! Schema::hasTable('case_messages')) {
            Schema::create('case_messages', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('case_message_thread_id')->index();
                $table->unsignedBigInteger('formal_case_id')->index();
                $table->unsignedBigInteger('sender_id')->nullable()->index();
                $table->unsignedBigInteger('receiver_id')->nullable()->index();
                $table->string('receiver_role')->nullable();
                $table->text('message');
                $table->timestamp('read_at')->nullable();
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('case_messages');
        Schema::dropIfExists('case_message_threads');
    }
};
