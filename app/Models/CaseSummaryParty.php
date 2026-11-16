<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CaseSummaryParty extends Model
{
    protected $guarded = [];

    public function caseSummary()
    {
        return $this->belongsTo(CaseSummary::class);
    }
}
