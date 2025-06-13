<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class GaWorkingList extends Model
{
    protected $fillable = [
        'name',
        'progres',
        'description',
        'category',
        'start_date',
        'status',
        'due_date',
    ];

    protected $casts = [
        'start_date' => 'date',
        'due_date' => 'date',
    ];

    public function progressHistories(): HasMany
    {
        return $this->hasMany(GaProgressHistory::class);
    }
}
