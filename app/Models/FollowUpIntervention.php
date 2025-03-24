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
    ];
}
