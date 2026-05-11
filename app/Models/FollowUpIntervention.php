<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory; // Add this line

class FollowUpIntervention extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'central_id', // Fix the typo 'entral_id' to 'central_id'
        'user_id',
        'intervention_taken',
        'intervention_taken_date',
        'intervention_to_be_taken',
        'to_be_taken_date',
        'task_status',
        'task_completed_by',
        'task_completed_at',
    ];

    protected $casts = [
        'intervention_taken_date' => 'date',
        'to_be_taken_date' => 'date',
        'task_completed_at' => 'datetime',
    ];

    public function creator()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function completedBy()
    {
        return $this->belongsTo(User::class, 'task_completed_by');
    }

    public function formalCase()
    {
        return $this->belongsTo(FormalCase::class, 'central_id');
    }
}
