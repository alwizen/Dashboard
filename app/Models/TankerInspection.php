<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TankerInspection extends Model
{
    protected $fillable = [
        'tanker_id',
        'inspection_date',
        'comp_1_status',
        'comp_2_status', 
        'comp_3_status',
        'comp_4_status',
        'comp_5_status',
        'overall_status',
        'notes',
    ];

    protected $casts = [
        'inspection_date' => 'date',
    ];

    public function tanker(): BelongsTo
    {
        return $this->belongsTo(Tanker::class);
    }

    // Auto calculate overall status berdasarkan status compartment
    public function calculateOverallStatus(): string
    {
        $statuses = [
            $this->comp_1_status,
            $this->comp_2_status,
            $this->comp_3_status,
            $this->comp_4_status,
            $this->comp_5_status,
        ];

        // Filter hanya status yang tidak null (sesuai jumlah comp tanker)
        $activeStatuses = array_filter($statuses, fn($status) => $status !== null);

        // Jika ada satu saja yang tidak kedap, maka overall = tidak kedap
        return in_array('tidak_kedap', $activeStatuses) ? 'tidak_kedap' : 'kedap';
    }

    // Boot method untuk auto calculate saat save
    protected static function boot()
    {
        parent::boot();

        static::saving(function ($inspection) {
            $inspection->overall_status = $inspection->calculateOverallStatus();
        });
    }

    // Accessor untuk label status
    public function getOverallStatusLabelAttribute(): string
    {
        return $this->overall_status === 'kedap' ? 'Kedap' : 'Tidak Kedap';
    }

    // Scope untuk filter berdasarkan status
    public function scopeKedap($query)
    {
        return $query->where('overall_status', 'kedap');
    }

    public function scopeTidakKedap($query)
    {
        return $query->where('overall_status', 'tidak_kedap');
    }
}