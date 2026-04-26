<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FileUpload extends Model
{
    protected $fillable = ['case_id', 'file_name', 'file_path', 'uploaded_by'];
}
