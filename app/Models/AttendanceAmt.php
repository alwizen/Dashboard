<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AttendanceAmt extends Model
{
    protected $fillable = [
        'driver_amt_id',
        'scanned_at',
        'status'
    ];

    public function driverAmt(): BelongsTo
    {
        return $this->belongsTo(DriverAmt::class);
    }
}
