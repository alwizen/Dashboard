<?php

namespace App\Observers;

use App\Models\MpsProgressHistory;

class MpsProgressHistoryObserver
{
    /**
     * Handle the MpsProgressHistory "created" event.
     */
    public function created(MpsProgressHistory $history)
    {
        $mps = $history->mpsWorkingList;

        $mps->progres = $history->progress;
    
        if ($history->progress >= 100) {
            $mps->status = 'completed';
        } elseif ($history->progress > 0 && $mps->status === 'pending') {
            $mps->status = 'in_progress';
        }
    
        $mps->save();
    }

    /**
     * Handle the MpsProgressHistory "updated" event.
     */
    public function updated(MpsProgressHistory $mpsProgressHistory): void
    {
        //
    }

    /**
     * Handle the MpsProgressHistory "deleted" event.
     */
    public function deleted(MpsProgressHistory $mpsProgressHistory): void
    {
        //
    }

    /**
     * Handle the MpsProgressHistory "restored" event.
     */
    public function restored(MpsProgressHistory $mpsProgressHistory): void
    {
        //
    }

    /**
     * Handle the MpsProgressHistory "force deleted" event.
     */
    public function forceDeleted(MpsProgressHistory $mpsProgressHistory): void
    {
        //
    }
}
