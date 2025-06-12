<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Carbon\Carbon;

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
        'comp',
        'transportir_id',
        'merk'
    ];

    protected $casts = [
        'kir_expiry' => 'date',
        'kim_expiry' => 'date',
    ];

    public function maintenances()
    {
        return $this->hasMany(TankerMaintenance::class);
    }

    public function kirHistories()
    {
        return $this->hasMany(TankerKirHistory::class);
    }

    public function kimHistories()
    {
        return $this->hasMany(TankerKimHistory::class);
    }


    public function transportir(): BelongsTo
    {
        return $this->belongsTo(Transportir::class);
    }

    public function inspections(): HasMany
    {
        return $this->hasMany(TankerInspection::class);
    }

    // Get latest inspection
    public function latestInspection()
    {
        return $this->hasOne(TankerInspection::class)->latestOfMany();
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

    // Get compartment labels for form
    public function getCompartmentLabelsAttribute(): array
    {
        $labels = [];
        for ($i = 1; $i <= $this->comp; $i++) {
            $labels[] = "Kompartemen $i";
        }
        return $labels;
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

    // Scope untuk filter tanker berdasarkan status inspeksi terakhir
    public function scopeWithLatestInspectionStatus(Builder $query, string $status = null)
    {
        return $query->with('latestInspection')
            ->when($status, function ($q) use ($status) {
                $q->whereHas('latestInspection', function ($inspectionQuery) use ($status) {
                    $inspectionQuery->where('overall_status', $status);
                });
            });
    }
}
