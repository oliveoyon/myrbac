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
        Schema::create('formal_cases', function (Blueprint $table) {
            $table->id();
            // 1. Primary info starts
            $table->string('institute')->nullable();
            $table->string('central_id')->nullable();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('district_id');
            $table->unsignedBigInteger('pngo_id');
            $table->string('status')->nullable();
            $table->string('full_name')->nullable();
            $table->string('nick_name')->nullable();
            $table->string('father_name')->nullable();
            $table->string('mother_name')->nullable();
            $table->string('sex')->nullable();
            $table->integer('age')->nullable();
            $table->string('disability')->default('no');
            $table->string('nationality')->nullable();
            $table->string('nid_passport')->nullable();
            $table->string('phone_number')->nullable();
            // Primary info end
            // 2. Session Info starts
            $table->text('address')->nullable();
            $table->date('interview_date')->nullable();
            $table->time('interview_time')->nullable();
            $table->string('interview_place')->nullable();
            // Session Info end
            // 3. personal info starts
            $table->string('marital_status')->nullable();
            $table->string('spouse_name')->nullable();
            $table->string('education_level')->nullable();
            $table->string('occupation')->nullable();
            $table->decimal('monthly_income', 10, 2)->nullable();
            $table->string('family_informed')->nullable();
            // personal info end
            // if prisoners are female starts
            $table->string('children_with_prisoner')->nullable();
            $table->string('child_sex')->nullable();
            $table->integer('child_age')->nullable();
            // if prisoners are female ends
            // 4. guardian info starts
            $table->string('has_guardian')->nullable();
            $table->string('guardian_name')->nullable();
            $table->string('guardian_phone')->nullable();
            $table->text('guardian_address')->nullable();
            $table->string('guardian_relation')->nullable();
            $table->string('guardian_surety')->nullable();
            // guardian info end
            // 5. lawyers information starts
            $table->string('has_lawyer')->nullable();
            $table->string('lawyer_type')->nullable();
            $table->string('lawyer_name')->nullable();
            $table->string('lawyer_membership')->nullable();
            $table->string('lawyer_phone')->nullable();
            // lawyers information end
            // 6. incident info starts
            $table->text('incident_details')->nullable();
            // incident info end
            // 7. court and police support info starts
            $table->string('custody_status')->nullable();
            $table->text('charges_details')->nullable();
            $table->date('arrest_date')->nullable();
            $table->string('case_no')->nullable();
            // court and police support info end
            // 8. Nature of Assistance
            $table->date('family_communication_date')->nullable();
            $table->string('legal_representation')->nullable();
            $table->date('legal_representation_date')->nullable();
            $table->date('collected_vokalatnama_date')->nullable();
            $table->date('collected_case_doc')->nullable();
            $table->text('identify_sureties')->nullable();
            $table->date('witness_communication_date')->nullable();
            $table->date('medical_report_date')->nullable();
            $table->date('legal_assistance_date')->nullable();
            $table->date('assistance_under_custody_date')->nullable();
            $table->text('referral_service')->nullable();
            $table->date('referral_service_date')->nullable();
            // Nature of Assistance end
            // 9. Result of Assistance
            $table->date('resolved_dispute_date')->nullable();
            $table->date('appoint_lawyer_date')->nullable();
            $table->string('release_status')->nullable();
            // 9. Result of Assistance end
            // 10. Legal aid office information starts
            $table->decimal('fine_amount', 10, 2)->nullable();
            $table->date('release_status_date')->nullable();
            $table->string('application_mode')->nullable();
            $table->date('application_mode_date')->nullable();
            $table->string('received_application')->nullable();
            $table->string('reference_no')->nullable();
            $table->string('type_of_service')->nullable();
            $table->date('type_of_service_date')->nullable();
            // Legal aid office information end
            // 11. Service Description
            $table->text('service_description')->nullable();
            // 11. Service Description end
            // 12. Support in prison starts
            $table->string('source_of_interview')->nullable();
            $table->string('prison_reg_no')->nullable();
            $table->string('section_no')->nullable();
            $table->string('present_court')->nullable();
            $table->string('lockup_no')->nullable();
            $table->date('entry_date')->nullable();
            $table->string('case_transferred')->nullable();
            $table->string('current_court')->nullable();
            $table->string('case_status')->nullable();
            $table->text('co_offenders')->nullable();
            $table->date('next_court_date')->nullable();
            $table->text('facts_of_case')->nullable();
            // Support in prison end
            // 13. Imprisonment Information
            $table->text('imprisonment_condition')->nullable();
            $table->string('imprisonment_status')->nullable();
            $table->text('special_condition')->nullable();
            $table->date('surrender_date')->nullable();
            // Imprisonment Information end
            // 14. Support in prison
            $table->date('prison_family_communication')->nullable();
            $table->date('prison_legal_representation')->nullable();
            $table->date('prison_legal_representation_date')->nullable();
            $table->date('next_court_collection_date')->nullable();
            $table->text('collected_case_doc_prison')->nullable();
            $table->string('identify_sureties_prison_nid')->nullable();
            $table->string('identify_sureties_prison_phone')->nullable();
            $table->date('witness_communication_prison')->nullable();
            $table->date('bail_bond_submission')->nullable();
            $table->date('court_order_communication')->nullable();
            $table->date('application_certified_copies')->nullable();
            $table->text('appeal_assistance')->nullable();
            $table->text('ministerial_communication')->nullable();
            $table->text('other_legal_assistance')->nullable();
            $table->date('other_legal_assistance_date')->nullable();
            // Support in prison end
            // 15. Result of Assistance in Prison
            $table->date('released_on')->nullable();
            $table->date('released_on_date')->nullable();
            $table->text('send_to')->nullable();
            $table->date('send_to_date')->nullable();
            $table->integer('convicted_length')->nullable();
            $table->date('convicted_sentence_expire')->nullable();
            $table->text('result_of_appeal')->nullable();
            $table->date('date_of_reliefe')->nullable();
            // Result of Assistance in Prison end
            $table->date('file_closure_date')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('formal_cases');
    }
};
