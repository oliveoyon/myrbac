<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Todo extends Model
{
    use HasFactory;

    public const STATUS_PENDING = 'pending';
    public const STATUS_IN_PROGRESS = 'in_progress';
    public const STATUS_DONE = 'done';

    protected $fillable = [
        'user_id',
        'district_id',
        'pngo_id',
        'task_date',
        'title',
        'description',
        'status',
        'completed_at',
    ];

    protected $casts = [
        'task_date' => 'date',
        'completed_at' => 'datetime',
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
