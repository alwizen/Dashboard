<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProgramValue extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'program_id',
        'criteria_template_id',
        'value',
        'updated_at'
    ];

    protected $casts = [
        'updated_at' => 'datetime'
    ];

    // Relationships
    public function program(): BelongsTo
    {
        return $this->belongsTo(Program::class);
    }

    public function criteriaTemplate(): BelongsTo
    {
        return $this->belongsTo(CriteriaTemplate::class);
    }

    // Methods
    public function getCastedValue()
    {
        if (!$this->criteriaTemplate) {
            return $this->value;
        }

        return match($this->criteriaTemplate->field_type) {
            'number', 'percentage' => is_numeric($this->value) ? (float) $this->value : null,
            'boolean' => filter_var($this->value, FILTER_VALIDATE_BOOLEAN),
            'date' => $this->value ? \Carbon\Carbon::parse($this->value)->format('Y-m-d') : null,
            'datetime' => $this->value ? \Carbon\Carbon::parse($this->value) : null,
            default => $this->value
        };
    }

    public function getFormattedValue()
    {
        $castedValue = $this->getCastedValue();

        if (!$this->criteriaTemplate) {
            return $castedValue;
        }

        return match($this->criteriaTemplate->field_type) {
            'percentage' => $castedValue . '%',
            'number' => $castedValue . ($this->criteriaTemplate->unit ? ' ' . $this->criteriaTemplate->unit : ''),
            'boolean' => $castedValue ? 'Yes' : 'No',
            'date' => $castedValue ? \Carbon\Carbon::parse($castedValue)->format('d/m/Y') : null,
            'datetime' => $castedValue ? $castedValue->format('d/m/Y H:i') : null,
            'select' => $this->criteriaTemplate->getSelectOptions()[$castedValue] ?? $castedValue,
            default => $castedValue
        };
    }

    // Events
    protected static function boot()
    {
        parent::boot();

        static::saving(function ($model) {
            $model->updated_at = now();
        });
    }
}
