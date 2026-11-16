<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CaseSummaryAttachment extends Model
{
    protected $guarded = [];

    public function caseSummary() { return $this->belongsTo(CaseSummary::class); }
    public function uploader() { return $this->belongsTo(User::class, 'uploaded_by'); }
}
