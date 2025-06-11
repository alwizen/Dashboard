<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TankerBbk extends Model
{
    protected $fillable = [
        'nopol',
        'product',
        'capacity',
        'kir_expiry',
        'kim_expiry',
        'status', // available, in_use, under_maintenance
        'note',
        'comp',
        'transportir_id',
        'merk'
    ];

    protected $casts = [
        'kir_expiry' => 'date',
        'kim_expiry' => 'date',
    ];

    public function transportir(): BelongsTo
    {
        return $this->belongsTo(Transportir::class);
    }
    public function getStatusLabelAttribute()
    {
        return match ($this->status) {
            'available' => 'Available',
            'under_maintenance' => 'Under Maintenance',
            'afkir' => 'AFKIR',
            default => 'Unknown',
        };
    }
    //peringatan masa berakhir
    public function scopeExpiringSoon(Builder $query): Builder
    {
        $today = now();
        $nextWeek = now()->addWeek();

        return $query->where(function ($q) use ($today, $nextWeek) {
            $q->whereBetween('kir_expiry', [$today, $nextWeek])
                ->orWhereBetween('kim_expiry', [$today, $nextWeek]);
        });
    }
}

