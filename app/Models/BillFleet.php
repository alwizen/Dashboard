<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BillFleet extends Model
{
    protected $fillable = [
        'month',
        'year',
        'bill_name',
        'bill_value',
        'progress',
        'status'
    ];

    public static function boot()
    {
        parent::boot();

        static::saving(function ($model) {
            if ($model->progress === 'PA') {
                $model->status = 'done';
            }
        });
    }
}
