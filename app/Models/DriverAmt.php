<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class DriverAmt extends Model
{
    protected $fillable = [
        'nip',
        'name',
        'position',
        'note',
        'rfid_code'
    ];

    public function attendances(): HasMany
    {
        return $this->hasMany(\App\Models\AttendanceAmt::class);
    }
}
