<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TankerMaintenance extends Model
{
    protected $fillable = [
        'tanker_id',
        'date',
        'description',
        'photo'
    ];

    public function tanker(): BelongsTo
    {
        return $this->belongsTo(Tanker::class);
    }
}
