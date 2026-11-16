<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CaseSummaryFollowUp extends Model
{
    protected $guarded = [];

    protected function casts(): array
    {
        return [
            'follow_up_date' => 'date',
            'intervention_to_be_taken_date' => 'date',
        ];
    }

    public function caseSummary() { return $this->belongsTo(CaseSummary::class); }
    public function creator() { return $this->belongsTo(User::class, 'created_by'); }
}
