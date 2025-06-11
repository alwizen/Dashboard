<?php

namespace App\Policies;

use App\Models\User;
use App\Models\DailySample;
use Illuminate\Auth\Access\HandlesAuthorization;

class DailySamplePolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->can('view_any_daily::sample');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, DailySample $dailySample): bool
    {
        return $user->can('view_daily::sample');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->can('create_daily::sample');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, DailySample $dailySample): bool
    {
        return $user->can('update_daily::sample');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, DailySample $dailySample): bool
    {
        return $user->can('delete_daily::sample');
    }

    /**
     * Determine whether the user can bulk delete.
     */
    public function deleteAny(User $user): bool
    {
        return $user->can('delete_any_daily::sample');
    }

    /**
     * Determine whether the user can permanently delete.
     */
    public function forceDelete(User $user, DailySample $dailySample): bool
    {
        return $user->can('force_delete_daily::sample');
    }

    /**
     * Determine whether the user can permanently bulk delete.
     */
    public function forceDeleteAny(User $user): bool
    {
        return $user->can('force_delete_any_daily::sample');
    }

    /**
     * Determine whether the user can restore.
     */
    public function restore(User $user, DailySample $dailySample): bool
    {
        return $user->can('restore_daily::sample');
    }

    /**
     * Determine whether the user can bulk restore.
     */
    public function restoreAny(User $user): bool
    {
        return $user->can('restore_any_daily::sample');
    }

    /**
     * Determine whether the user can replicate.
     */
    public function replicate(User $user, DailySample $dailySample): bool
    {
        return $user->can('replicate_daily::sample');
    }

    /**
     * Determine whether the user can reorder.
     */
    public function reorder(User $user): bool
    {
        return $user->can('reorder_daily::sample');
    }
}
