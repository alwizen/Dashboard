<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CriteriaTemplate extends Model
{
    use HasFactory;

    protected $fillable = [
        'division_id',
        'field_name',
        'field_type',
        'field_options',
        'is_required',
        'display_order',
        'unit',
        'description',
        'is_active'
    ];

    protected $casts = [
        'field_options' => 'array',
        'is_required' => 'boolean',
        'is_active' => 'boolean',
        'display_order' => 'integer'
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

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('display_order');
    }

    public function scopeByFieldType($query, $type)
    {
        return $query->where('field_type', $type);
    }

    // Methods
    public function getSelectOptions()
    {
        if ($this->field_type === 'select' && isset($this->field_options['options'])) {
            return $this->field_options['options'];
        }
        return [];
    }

    public function getValidationRules()
    {
        $rules = [];

        if ($this->is_required) {
            $rules[] = 'required';
        }

        switch ($this->field_type) {
            case 'number':
            case 'percentage':
                $rules[] = 'numeric';
                if ($this->field_type === 'percentage') {
                    $rules[] = 'min:0';
                    $rules[] = 'max:100';
                }
                break;
            case 'date':
                $rules[] = 'date';
                break;
            case 'datetime':
                $rules[] = 'date_format:Y-m-d H:i:s';
                break;
            case 'boolean':
                $rules[] = 'boolean';
                break;
            case 'select':
                if (!empty($this->getSelectOptions())) {
                    $rules[] = 'in:' . implode(',', array_keys($this->getSelectOptions()));
                }
                break;
        }

        return $rules;
    }
}
