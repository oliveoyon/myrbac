<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Log extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'action',
        'data',
        'ip_address',
    ];

    protected $casts = [
        'data' => 'array', // Automatically cast the 'data' field to an array
    ];

    // Optionally define a relationship to the User model
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
