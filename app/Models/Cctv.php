<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cctv extends Model
{
    protected $fillable = [
        'code',
        'location',
        'status',
        'notes',
    ];

    protected $casts = [
        'status' => 'boolean',
    ];

}
