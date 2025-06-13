<?php

namespace App\Observers;

use App\Models\GaProgressHistory;

class GaProgressHistoryObserver
{
    /**
     * Handle the GaProgressHistory "created" event.
     */
    public function created(GaProgressHistory $history)
    {
        $ga = $history->gaWorkingList;

        $ga->progres = $history->progress;

        if ($history->progress >= 100) {
            $ga->status = 'completed';
        }

        $ga->save();
    }

    /**
     * Handle the GaProgressHistory "updated" event.
     */
    public function updated(GaProgressHistory $GaProgressHistory): void
    {
        //
    }

    /**
     * Handle the GaProgressHistory "deleted" event.
     */
    public function deleted(GaProgressHistory $GaProgressHistory): void
    {
        //
    }

    /**
     * Handle the GaProgressHistory "restored" event.
     */
    public function restored(GaProgressHistory $GaProgressHistory): void
    {
        //
    }

    /**
     * Handle the GaProgressHistory "force deleted" event.
     */
    public function forceDeleted(GaProgressHistory $GaProgressHistory): void
    {
        //
    }
}
