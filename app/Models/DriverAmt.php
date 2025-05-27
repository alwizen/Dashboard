<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DriverAmt extends Model
{
   protected $fillable = [
        'nip',
        'name',
        'position',
        'note',
    ];

}
