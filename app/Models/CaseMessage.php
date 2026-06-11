<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CaseMessage extends Model
{
    protected $fillable = [
        'case_message_thread_id',
        'formal_case_id',
        'sender_id',
        'receiver_id',
        'receiver_role',
        'message',
        'read_at',
    ];

    protected $casts = [
        'read_at' => 'datetime',
    ];

    public function thread()
    {
        return $this->belongsTo(CaseMessageThread::class, 'case_message_thread_id');
    }

    public function formalCase()
    {
        return $this->belongsTo(FormalCase::class, 'formal_case_id');
    }

    public function sender()
    {
        return $this->belongsTo(User::class, 'sender_id');
    }

    public function receiver()
    {
        return $this->belongsTo(User::class, 'receiver_id');
    }
}
