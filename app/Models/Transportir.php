<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Transportir extends Model
{
    protected $fillable = [
        'name',
        'email',
        'phone',
        'address'
    ];

    public function Tanker(): HasMany
    {
        return $this->hasMany(Transportir::class);
    }
}
