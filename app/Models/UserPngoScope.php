<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserPngoScope extends Model
{
    protected $fillable = [
        'user_id',
        'district_id',
        'pngo_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
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
