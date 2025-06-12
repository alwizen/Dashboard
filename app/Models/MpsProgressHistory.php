<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MpsProgressHistory extends Model
{
    protected $fillable = [
        'mps_working_list_id',
        'progress',
        'note',
        'progress_date',
    ];

    protected $casts = [
        'progress_date' => 'date',
    ];

    public function mpsWorkingList(): BelongsTo
    {
        return $this->belongsTo(MpsWorkingList::class);
    }
}