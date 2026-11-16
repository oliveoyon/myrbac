<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('case_summaries', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->id();

            // Identity, ownership, scope, and verification workflow.
            $table->string('central_id', 50)->unique();
            $table->string('summary_type', 50);
            $table->unsignedBigInteger('district_id')->index();
            $table->unsignedBigInteger('pngo_id')->index();
            $table->unsignedBigInteger('court_id')->index();
            $table->date('summary_date');
            $table->unsignedTinyInteger('status')->default(1)->index();
            $table->unsignedBigInteger('created_by')->index();
            $table->unsignedBigInteger('updated_by')->nullable()->index();
            $table->unsignedBigInteger('dpo_verified_by')->nullable()->index();
            $table->timestamp('dpo_verified_at')->nullable();
            $table->text('dpo_verification_remarks')->nullable();

            // Section 1: Case identification.
            $table->string('case_nature', 50);
            $table->string('case_number', 125)->index();
            $table->json('compoundable_sections')->nullable();
            $table->string('non_compoundable_section', 255)->nullable();
            $table->text('case_section_comments')->nullable();
            $table->date('case_filing_date');

            // Section 2: Case profile.
            $table->text('offence_subject')->nullable();
            $table->string('current_case_status', 125);
            $table->string('current_case_status_other', 255)->nullable();
            $table->unsignedInteger('hearing_count');
            $table->unsignedInteger('trial_hearing_count');
            $table->date('last_hearing_date');
            $table->date('next_hearing_date');
            $table->string('accused_status', 125)->nullable();
            $table->string('accused_status_other', 255)->nullable();
            $table->unsignedInteger('number_of_witnesses')->nullable();
            $table->unsignedInteger('witnesses_examined')->nullable();

            // Section 4: Barriers preventing progress.
            $table->boolean('missing_documents')->default(false);
            $table->json('missing_document_types')->nullable();
            $table->text('missing_documents_other')->nullable();
            $table->text('missing_documents_remarks')->nullable();

            $table->boolean('absent_witnesses')->default(false);
            $table->json('absent_witnesses_type')->nullable();
            $table->string('government_witness_type', 125)->nullable();
            $table->string('government_witness_other', 255)->nullable();
            $table->text('absent_witness_information')->nullable();
            $table->text('absent_witnesses_remarks')->nullable();

            $table->boolean('procedural_delays')->default(false);
            $table->string('procedural_delays_type', 125)->nullable();
            $table->text('procedural_delays_other')->nullable();
            $table->text('procedural_delays_remarks')->nullable();

            $table->boolean('adjournment_by_parties')->default(false);
            $table->text('adjournment_by_parties_remarks')->nullable();
            $table->boolean('adjournment_by_court')->default(false);
            $table->text('adjournment_by_court_remarks')->nullable();
            $table->boolean('lack_of_lawyer')->default(false);
            $table->text('lack_of_lawyer_remarks')->nullable();
            $table->boolean('lack_of_prosecutor')->default(false);
            $table->text('lack_of_prosecutor_remarks')->nullable();
            $table->boolean('settled_outside_court')->default(false);
            $table->text('settled_outside_court_remarks')->nullable();
            $table->boolean('death_of_accused')->default(false);
            $table->text('death_of_accused_remarks')->nullable();
            $table->boolean('death_of_witness')->default(false);
            $table->text('death_of_witness_remarks')->nullable();
            $table->boolean('pending_for_argument')->default(false);
            $table->text('pending_for_argument_remarks')->nullable();
            $table->boolean('pending_for_judgment')->default(false);
            $table->text('pending_for_judgment_remarks')->nullable();
            $table->boolean('dismissed_but_pending')->default(false);
            $table->text('dismissed_but_pending_remarks')->nullable();
            $table->boolean('administrative_issues')->default(false);
            $table->text('administrative_issues_specify')->nullable();
            $table->text('administrative_issues_remarks')->nullable();

            // Section 5: Proposed disposal route or solution.
            $table->boolean('priority_hearing')->default(false);
            $table->boolean('mediation_settlement')->default(false);
            $table->boolean('diversion_to_probation')->default(false);
            $table->boolean('withdrawal')->default(false);
            $table->boolean('administrative_followup')->default(false);
            $table->boolean('case_scheduling')->default(false);
            $table->boolean('other_solution')->default(false);
            $table->text('other_solution_specify')->nullable();
            $table->text('solution_remarks')->nullable();

            // Section 6: Intervention taken by paralegal.
            $table->date('collect_case_documents_date')->nullable();
            $table->date('communicate_witness_date')->nullable();
            $table->date('communicate_parties_date')->nullable();
            $table->date('refer_mediation_date')->nullable();
            $table->string('refer_mediation_destination', 125)->nullable();
            $table->text('refer_mediation_destination_details')->nullable();
            $table->date('present_judge_date')->nullable();
            $table->date('present_judicial_conference_date')->nullable();
            $table->text('other_paralegal_intervention_details')->nullable();
            $table->date('other_paralegal_intervention_date')->nullable();

            // Section 7: Intervention taken by judiciary.
            $table->string('judicial_mediation_destination', 125)->nullable();
            $table->text('judicial_mediation_details')->nullable();
            $table->date('judicial_mediation_date')->nullable();
            $table->date('judiciary_priority_hearing_date')->nullable();
            $table->date('judiciary_diversion_to_probation_date')->nullable();
            $table->date('judiciary_withdrawal_date')->nullable();
            $table->date('judiciary_administrative_followup_date')->nullable();
            $table->date('judiciary_case_scheduling_date')->nullable();
            $table->text('judiciary_other_action_details')->nullable();
            $table->date('judiciary_other_action_date')->nullable();

            // Section 8: Case disposal. Outcomes are mutually exclusive in the form.
            $table->boolean('disposed_through_mediation')->default(false);
            $table->date('mediation_disposal_date')->nullable();
            $table->boolean('disposed_by_judgment')->default(false);
            $table->text('judgment_details')->nullable();
            $table->boolean('judgment_written_delivered')->default(false);
            $table->boolean('decree_pronounced')->default(false);
            $table->date('judgment_disposal_date')->nullable();
            $table->boolean('case_dismissed')->default(false);
            $table->date('case_dismissed_date')->nullable();
            $table->boolean('acquitted')->default(false);
            $table->string('acquittal_reason', 125)->nullable();
            $table->date('acquittal_date')->nullable();

            $table->timestamp('submitted_at')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        // Section 3: Both party groups support multiple rows.
        Schema::create('case_summary_parties', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->id();
            $table->unsignedBigInteger('case_summary_id')->index();
            $table->string('party_type', 30)->index();
            $table->unsignedInteger('sort_order')->default(1);
            $table->string('name', 255);
            $table->string('sex', 50);
            $table->unsignedTinyInteger('age')->nullable();
            $table->string('disability', 20)->nullable();
            $table->string('income', 50)->nullable();
            $table->string('phone', 30)->nullable();
            $table->string('representation', 125)->nullable();
            $table->string('representation_other', 255)->nullable();
            $table->string('lawyer_name', 255)->nullable();
            $table->string('lawyer_phone', 30)->nullable();
            $table->timestamps();

            $table->index(['case_summary_id', 'party_type'], 'case_summary_party_type_index');
        });

        // Section 1.4: A case summary may be linked to multiple managed Acts.
        Schema::create('case_summary_act', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->id();
            $table->unsignedBigInteger('case_summary_id')->index();
            $table->unsignedBigInteger('act_id')->index();
            $table->timestamps();

            $table->unique(['case_summary_id', 'act_id'], 'case_summary_act_unique');
        });

        // Section 9: Every edit may append a new follow-up record.
        Schema::create('case_summary_follow_ups', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->id();
            $table->unsignedBigInteger('case_summary_id')->index();
            $table->date('follow_up_date')->nullable();
            $table->text('follow_up_action')->nullable();
            $table->date('intervention_to_be_taken_date')->nullable();
            $table->text('interventions_to_be_taken')->nullable();
            $table->unsignedBigInteger('created_by')->index();
            $table->timestamps();
        });

        // Section 10: Supporting evidence files are stored separately.
        Schema::create('case_summary_attachments', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->id();
            $table->unsignedBigInteger('case_summary_id')->index();
            $table->string('original_file_name', 255);
            $table->string('stored_file_name', 255);
            $table->string('file_path', 500);
            $table->string('mime_type', 125);
            $table->unsignedBigInteger('file_size');
            $table->unsignedBigInteger('uploaded_by')->index();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('case_summary_attachments');
        Schema::dropIfExists('case_summary_follow_ups');
        Schema::dropIfExists('case_summary_act');
        Schema::dropIfExists('case_summary_parties');
        Schema::dropIfExists('case_summaries');
    }
};
