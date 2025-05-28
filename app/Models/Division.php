<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Division extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'code',
        'description',
        'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    // Relationships
    public function programs(): HasMany
    {
        return $this->hasMany(Program::class);
    }

    public function criteriaTemplates(): HasMany
    {
        return $this->hasMany(CriteriaTemplate::class);
    }

    public function dailyReports(): HasMany
    {
        return $this->hasMany(DailyReport::class);
    }

    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    // Accessors & Mutators
    public function getActiveTemplatesAttribute()
    {
        return $this->criteriaTemplates()->where('is_active', true)->orderBy('display_order')->get();
    }

    public function getActiveProgramsCountAttribute()
    {
        return $this->programs()->where('is_active', true)->count();
    }

    public function getCompletedProgramsCountAttribute()
    {
        return $this->programs()->where('status', 'completed')->count();
    }

    public function getOverallProgressAttribute()
    {
        $activePrograms = $this->programs()->where('is_active', true)->get();
        if ($activePrograms->isEmpty()) {
            return 0;
        }

        return round($activePrograms->avg('overall_progress'), 2);
    }
}
