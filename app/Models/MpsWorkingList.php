<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class MpsWorkingList extends Model
{
    protected $fillable = [
        'name',
        'progres',
        'description',
        'category_id',
        'start_date',
        'status',
        'due_date',
    ];

    protected $casts = [
        'start_date' => 'date',
        'due_date' => 'datetime',
    ];

    public function mpsCategory()
    {
        return $this->belongsTo(MpsWorkingListCategory::class, 'category_id');
    }
    public function progressHistories(): HasMany
    {
        return $this->hasMany(MpsProgressHistory::class);
    }
}
