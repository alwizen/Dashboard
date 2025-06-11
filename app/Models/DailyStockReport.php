<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DailyStockReport extends Model
{
    protected $fillable = [
        'report_date',
        'product_id',
        'tank_number',
        'safe_cap_level',
        'safe_cap_volume',
        'opening_stock_level',
        'opening_stock_volume',
        'current_stock_level',
        'current_stock_volume',
        'current_air_level',
        'current_air_volume',
        'dead_stock',
        'pump_stock',
        'ullage',
        'ddt',
        'cd',
        'floating_tegak',
        'stafle_moss',
        'next_supply',
        'receipt',
        'actual_throughput',
        'working_loss_liter',
        'working_loss_percent'
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
