<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CaseSummary extends Model
{
    use SoftDeletes;

    public const STATUS_SUBMITTED = 1;
    public const STATUS_DPO_VERIFIED = 2;

    protected $guarded = [];

    protected function casts(): array
    {
        return [
            'compoundable_sections' => 'array',
            'missing_document_types' => 'array',
            'absent_witnesses_type' => 'array',
            'summary_date' => 'date',
            'case_filing_date' => 'date',
            'last_hearing_date' => 'date',
            'next_hearing_date' => 'date',
            'dpo_verified_at' => 'datetime',
            'submitted_at' => 'datetime',
            'missing_documents' => 'boolean',
            'absent_witnesses' => 'boolean',
            'procedural_delays' => 'boolean',
            'adjournment_by_parties' => 'boolean',
            'adjournment_by_court' => 'boolean',
            'lack_of_lawyer' => 'boolean',
            'lack_of_prosecutor' => 'boolean',
            'settled_outside_court' => 'boolean',
            'death_of_accused' => 'boolean',
            'death_of_witness' => 'boolean',
            'pending_for_argument' => 'boolean',
            'pending_for_judgment' => 'boolean',
            'dismissed_but_pending' => 'boolean',
            'administrative_issues' => 'boolean',
            'priority_hearing' => 'boolean',
            'mediation_settlement' => 'boolean',
            'diversion_to_probation' => 'boolean',
            'withdrawal' => 'boolean',
            'administrative_followup' => 'boolean',
            'case_scheduling' => 'boolean',
            'other_solution' => 'boolean',
            'disposed_through_mediation' => 'boolean',
            'disposed_by_judgment' => 'boolean',
            'judgment_written_delivered' => 'boolean',
            'decree_pronounced' => 'boolean',
            'case_dismissed' => 'boolean',
            'acquitted' => 'boolean',
        ];
    }

    public function district() { return $this->belongsTo(District::class); }
    public function pngo() { return $this->belongsTo(Pngo::class); }
    public function court() { return $this->belongsTo(Court::class); }
    public function creator() { return $this->belongsTo(User::class, 'created_by'); }
    public function updater() { return $this->belongsTo(User::class, 'updated_by'); }
    public function dpoVerifier() { return $this->belongsTo(User::class, 'dpo_verified_by'); }
    public function parties() { return $this->hasMany(CaseSummaryParty::class)->orderBy('party_type')->orderBy('sort_order'); }
    public function acts() { return $this->belongsToMany(Act::class, 'case_summary_act')->withTimestamps(); }
    public function followUps() { return $this->hasMany(CaseSummaryFollowUp::class)->latest('follow_up_date'); }
    public function attachments() { return $this->hasMany(CaseSummaryAttachment::class); }
}
