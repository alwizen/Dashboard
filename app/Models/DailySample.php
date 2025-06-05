<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DailySample extends Model
{
    protected $fillable = [
        'sample_date',
        'photo',
    ];

    protected $casts = [
        'sample_date' => 'date',
    ];
    public function dailySampleItems(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(DailySampleItem::class);
    }
    
}
