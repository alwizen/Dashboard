<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes; // jika menggunakan soft delete

class WorkList extends Model
{
    // use SoftDeletes; // uncomment jika menggunakan soft delete

    protected $fillable = [
        'title',
        'type',
        'department_id',
        'progress',
        'status',
        'due_date',
        'description',
    ];

    protected $casts = [
        'due_date' => 'date',
        'progress' => 'integer',
    ];

    /**
     * Relationship ke Department
     */
    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class);
    }

    /**
     * Relationship ke WorkListHistory
     */
    public function histories(): HasMany
    {
        return $this->hasMany(WorkListHistory::class);
    }

    /**
     * Get progress terbaru dari history
     */
    public function getLatestProgressAttribute(): int
    {
        $latestHistory = $this->histories()->latest()->first();
        return $latestHistory ? $latestHistory->progress : $this->progress;
    }

    /**
     * Get history terbaru
     */
    public function getLatestHistoryAttribute(): ?WorkListHistory
    {
        return $this->histories()->latest()->first();
    }
}
