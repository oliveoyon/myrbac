<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CaseMessageThread extends Model
{
    protected $fillable = [
        'formal_case_id',
        'status',
        'created_by',
        'resolved_by',
        'resolved_at',
    ];

    protected $casts = [
        'resolved_at' => 'datetime',
    ];

    public function formalCase()
    {
        return $this->belongsTo(FormalCase::class, 'formal_case_id');
    }

    public function messages()
    {
        return $this->hasMany(CaseMessage::class);
    }

    public function latestMessage()
    {
        return $this->hasOne(CaseMessage::class)->latestOfMany();
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function resolver()
    {
        return $this->belongsTo(User::class, 'resolved_by');
    }
}
