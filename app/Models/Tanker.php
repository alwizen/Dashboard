<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tanker extends Model
{
   protected $fillable = [
        'nopol',
        'product',
        'capacity',
        'kir_expiry',
        'kim_expiry',
        'status', // available, in_use, under_maintenance
        'note',
    ];

    protected $casts = [
        'kir_expiry' => 'date',
        'kim_expiry' => 'date',
    ];

    public function getStatusLabelAttribute()
    {
        return match ($this->status) {
            'available' => 'Available',
            'under_maintenance' => 'Under Maintenance',
            'afkir' => 'AFKIR',
            default => 'Unknown',
        };
    }
}
