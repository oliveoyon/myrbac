<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EndorsedResultDocument extends Model
{
    use HasFactory;

    protected $fillable = [
        'district_id',
        'pngo_id',
        'reporting_month',
        'document_title',
        'original_file_name',
        'stored_file_name',
        'file_path',
        'mime_type',
        'file_size',
        'uploaded_by',
        'updated_by',
    ];

    protected function casts(): array
    {
        return [
            'reporting_month' => 'date',
            'file_size' => 'integer',
        ];
    }

    public function district()
    {
        return $this->belongsTo(District::class);
    }

    public function pngo()
    {
        return $this->belongsTo(Pngo::class);
    }

    public function uploader()
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }

    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
}
