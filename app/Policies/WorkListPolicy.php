<?php

namespace App\Policies;

use App\Models\User;
use App\Models\WorkList;
use Illuminate\Auth\Access\HandlesAuthorization;

class WorkListPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->can('view_any_work::list');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, WorkList $workList): bool
    {
        return $user->can('view_work::list');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->can('create_work::list');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, WorkList $workList): bool
    {
        return $user->can('update_work::list');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, WorkList $workList): bool
    {
        return $user->can('delete_work::list');
    }

    /**
     * Determine whether the user can bulk delete.
     */
    public function deleteAny(User $user): bool
    {
        return $user->can('delete_any_work::list');
    }

    /**
     * Determine whether the user can permanently delete.
     */
    public function forceDelete(User $user, WorkList $workList): bool
    {
        return $user->can('force_delete_work::list');
    }

    /**
     * Determine whether the user can permanently bulk delete.
     */
    public function forceDeleteAny(User $user): bool
    {
        return $user->can('force_delete_any_work::list');
    }

    /**
     * Determine whether the user can restore.
     */
    public function restore(User $user, WorkList $workList): bool
    {
        return $user->can('restore_work::list');
    }

    /**
     * Determine whether the user can bulk restore.
     */
    public function restoreAny(User $user): bool
    {
        return $user->can('restore_any_work::list');
    }

    /**
     * Determine whether the user can replicate.
     */
    public function replicate(User $user, WorkList $workList): bool
    {
        return $user->can('replicate_work::list');
    }

    /**
     * Determine whether the user can reorder.
     */
    public function reorder(User $user): bool
    {
        return $user->can('reorder_work::list');
    }
}
