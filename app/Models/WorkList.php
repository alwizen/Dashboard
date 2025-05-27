<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WorkList extends Model
{
    protected $fillable = [
        'title',
        'type',
        'description',
        'department_id',
        'status',
        'progress',
        'due_date',
    ];

    public function department()
    {
        return $this->belongsTo(Department::class);
    }
    protected static function booted()
    {
        static::saving(function ($workList) {
            if ($workList->progress === 0) {
                $workList->status = 'pending';
            } elseif ($workList->progress < 100) {
                $workList->status = 'in_progress';
            } else {
                $workList->status = 'completed';
            }
        });
    }

}