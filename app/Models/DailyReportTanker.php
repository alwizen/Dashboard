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
        'note',
    ];

    protected $casts = [
        'report_date' => 'date',
    ];

}
