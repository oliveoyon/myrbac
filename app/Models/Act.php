<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Act extends Model
{
    protected $fillable = ['title', 'act_number', 'year', 'url'];
}
