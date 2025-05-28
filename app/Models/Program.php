<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Program extends Model
{
    use HasFactory;

    protected $fillable = [
        'division_id',
        'title',
        'description',
        'start_date',
        'due_date',
        'status',
        'overall_progress',
        'notes',
        'is_active'
    ];

    protected $casts = [
        'start_date' => 'date',
        'due_date' => 'date',
        'overall_progress' => 'decimal:2',
        'is_active' => 'boolean'
    ];

    // Relationships
    public function division(): BelongsTo
    {
        return $this->belongsTo(Division::class);
    }

    public function programValues(): HasMany
    {
        return $this->hasMany(ProgramValue::class);
    }

    public function histories(): HasMany
    {
        return $this->hasMany(ProgramHistory::class);
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    public function scopeOverdue($query)
    {
        return $query->where('due_date', '<', now())
            ->whereNotIn('status', ['completed', 'cancelled']);
    }

    public function scopeDueSoon($query, $days = 7)
    {
        return $query->whereBetween('due_date', [now(), now()->addDays($days)])
            ->whereNotIn('status', ['completed', 'cancelled']);
    }

    // Accessors & Mutators
    public function getIsOverdueAttribute()
    {
        return $this->due_date &&
            $this->due_date->isPast() &&
            !in_array($this->status, ['completed', 'cancelled']);
    }

    public function getDaysRemainingAttribute()
    {
        if (!$this->due_date) {
            return null;
        }

        return now()->diffInDays($this->due_date, false);
    }

    public function getStatusBadgeColorAttribute()
    {
        return match($this->status) {
            'not_started' => 'gray',
            'in_progress' => 'blue',
            'on_hold' => 'yellow',
            'completed' => 'green',
            'cancelled' => 'red',
            'overdue' => 'red',
            default => 'gray'
        };
    }

    public function getProgressBadgeColorAttribute()
    {
        return match(true) {
            $this->overall_progress >= 100 => 'green',
            $this->overall_progress >= 75 => 'blue',
            $this->overall_progress >= 50 => 'yellow',
            $this->overall_progress >= 25 => 'orange',
            default => 'red'
        };
    }

    // Methods
    public function updateStatus()
    {
        if ($this->overall_progress >= 100) {
            $this->status = 'completed';
        } elseif ($this->is_overdue && $this->status !== 'completed') {
            $this->status = 'overdue';
        } elseif ($this->overall_progress > 0 && $this->status === 'not_started') {
            $this->status = 'in_progress';
        }

        $this->save();
    }

    public function getCriteriaValue($fieldName)
    {
        $template = $this->division->criteriaTemplates()
            ->where('field_name', $fieldName)
            ->first();

        if (!$template) {
            return null;
        }

        $value = $this->programValues()
            ->where('criteria_template_id', $template->id)
            ->first();

        return $value ? $value->getCastedValue() : null;
    }

    public function setCriteriaValue($fieldName, $value)
    {
        $template = $this->division->criteriaTemplates()
            ->where('field_name', $fieldName)
            ->first();

        if (!$template) {
            return false;
        }

        return $this->programValues()->updateOrCreate(
            ['criteria_template_id' => $template->id],
            ['value' => $value]
        );
    }
}
