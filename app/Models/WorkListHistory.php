<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WorkListHistory extends Model
{
    protected $fillable = [
        'work_list_id',
        'progress',
        'note'
    ];

    public function workList()
    {
        return $this->belongsTo(WorkList::class);
    }

    protected static function booted()
    {
        // Ketika history baru dibuat atau diupdate
        static::created(function ($history) {
            \Log::info('WorkListHistory created event triggered', [
                'history_id' => $history->id,
                'work_list_id' => $history->work_list_id,
                'progress' => $history->progress
            ]);

            self::updateWorkListProgress($history);
        });

        static::updated(function ($history) {
            \Log::info('WorkListHistory updated event triggered', [
                'history_id' => $history->id,
                'work_list_id' => $history->work_list_id,
                'progress' => $history->progress
            ]);

            self::updateWorkListProgress($history);
        });

        // Ketika history dihapus, ambil progress dari history terbaru
        static::deleted(function ($history) {
            \Log::info('WorkListHistory deleted event triggered', [
                'history_id' => $history->id,
                'work_list_id' => $history->work_list_id,
            ]);

            $workList = $history->workList;

            // Cari history terbaru setelah yang dihapus
            $latestHistory = $workList->histories()
                ->orderBy('created_at', 'desc')
                ->first();

            if ($latestHistory) {
                // Jika masih ada history, gunakan progress dari history terbaru
                $workList->progress = $latestHistory->progress;
            } else {
                // Jika tidak ada history lagi, reset ke 0
                $workList->progress = 0;
            }

            $workList->save();
        });
    }

    /**
     * Update progress di WorkList berdasarkan history terbaru
     */
    private static function updateWorkListProgress($history)
    {
        \Log::info('Updating WorkList progress', [
            'history_progress' => $history->progress,
            'work_list_id' => $history->work_list_id
        ]);

        $workList = $history->workList;
        $oldProgress = $workList->progress;

        $workList->progress = $history->progress;

        // Auto update status berdasarkan progress
        if ($history->progress == 0) {
            $workList->status = 'pending';
        } elseif ($history->progress >= 100) {
            $workList->status = 'completed';
        } else {
            $workList->status = 'in_progress';
        }

        $saved = $workList->save();

        \Log::info('WorkList progress updated', [
            'work_list_id' => $workList->id,
            'old_progress' => $oldProgress,
            'new_progress' => $workList->progress,
            'status' => $workList->status,
            'saved' => $saved
        ]);
    }
}
