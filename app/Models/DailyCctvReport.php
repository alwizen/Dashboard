<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DailyCctvReport extends Model
{
    protected $fillable = [
        'report_date',
        'cctv_count',
        'active_cctv_count',
        'inactive_cctv_count',
        'report_details',
    ];

    protected $casts = [
        'report_date' => 'date',
    ];

    // public function scopeForDate($query, $date)
    // {
    //     return $query->where('report_date', $date);
    // }
}
