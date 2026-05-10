<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LsidRegister extends Model
{
    use HasFactory;

    protected $fillable = [
        'service_date',
        'district_id',
        'pngo_id',
        'receiver_name',
        'mobile_number',
        'sex',
        'other_information',
        'receiver_types',
        'interventions_taken',
        'service_types',
        'receiver_type_other',
        'service_type_other',
        'created_by',
    ];

    protected $casts = [
        'service_date' => 'date',
        'other_information' => 'array',
        'receiver_types' => 'array',
        'interventions_taken' => 'array',
        'service_types' => 'array',
    ];

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function district()
    {
        return $this->belongsTo(District::class);
    }

    public function pngo()
    {
        return $this->belongsTo(Pngo::class);
    }
}
