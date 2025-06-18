<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Spending extends Model
{
    protected $fillable = [
        'spanding_date',
        'name',
        'proof',
        'note',
        'category',
        'amount'
    ];
}
