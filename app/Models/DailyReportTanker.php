<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DailyReportTanker extends Model
{
    protected $fillable = [
        'report_date',
        'count_tankers',
        'count_tanker_under_maintenance',
        'count_tanker_afkir',
        'count_tanker_available',
        'total_capacity_available',
        'note',
    ];

    protected $casts = [
        'report_date' => 'date',
    ];

    protected static function booted()
{
    static::creating(function ($report) {
        $report->count_tankers = $report->count_tankers ?? Tanker::count();
        $report->count_tanker_under_maintenance = $report->count_tanker_under_maintenance ?? Tanker::where('status', 'under_maintenance')->count();
        $report->count_tanker_afkir = $report->count_tanker_afkir ?? Tanker::where('status', 'afkir')->count();
        $report->count_tanker_available = $report->count_tanker_available ?? Tanker::where('status', 'available')->count();
        $report->total_capacity_available = $report->total_capacity_available ?? Tanker::where('status', 'available')->sum('capacity');
    });
}


}
