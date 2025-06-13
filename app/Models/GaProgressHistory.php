<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class GaProgressHistory extends Model
{
    protected $fillable = [
        'ga_working_list_id',
        'progress',
        'note',
        'progress_date',
    ];

    protected $casts = [
        'progress_date' => 'date',
    ];

    public function gaWorkingList(): BelongsTo
    {
        return $this->belongsTo(GaWorkingList::class);
    }
}