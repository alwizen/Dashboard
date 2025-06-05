<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DailySampleItem extends Model
{
   protected $fillable = [
        'daily_sample_id',
        'product_id',
        'dencity',
        'temperature',
        'nil_water',
        'water_volume',
    ];

    protected $casts = [
        'dencity' => 'decimal:3',
        'temperature' => 'integer',
        'nil_water' => 'boolean',
        'water_volume' => 'decimal:2',
    ];

    public function dailySample(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(DailySample::class);
    }
    
    public function product(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}
