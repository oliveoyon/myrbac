<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Act extends Model
{
    protected $fillable = ['title', 'act_number', 'year', 'url'];

    public function caseSummaries()
    {
        return $this->belongsToMany(CaseSummary::class, 'case_summary_act')->withTimestamps();
    }
}
