<?php

namespace App\Policies;

use App\Models\Data\Prodi;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ProdiPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->can('view_any_prodi');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Prodi $prodi): bool
    {
        return $user->can('view_prodi');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->can('create_prodi');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Prodi $prodi): bool
    {
        return $user->can('update_prodi');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Prodi $prodi): bool
    {
        return $user->can('delete_prodi');
    }

    /**
     * Determine whether the user can bulk delete.
     */
    public function deleteAny(User $user): bool
    {
        return $user->can('delete_any_prodi');
    }

    /**
     * Determine whether the user can permanently delete.
     */
    public function forceDelete(User $user, Prodi $prodi): bool
    {
        return $user->can('force_delete_prodi');
    }

    /**
     * Determine whether the user can permanently bulk delete.
     */
    public function forceDeleteAny(User $user): bool
    {
        return $user->can('force_delete_any_prodi');
    }

    /**
     * Determine whether the user can restore.
     */
    public function restore(User $user, Prodi $prodi): bool
    {
        return $user->can('restore_prodi');
    }

    /**
     * Determine whether the user can bulk restore.
     */
    public function restoreAny(User $user): bool
    {
        return $user->can('restore_any_prodi');
    }

    /**
     * Determine whether the user can replicate.
     */
    public function replicate(User $user, Prodi $prodi): bool
    {
        return $user->can('replicate_prodi');
    }

    /**
     * Determine whether the user can reorder.
     */
    public function reorder(User $user): bool
    {
        return $user->can('reorder_prodi');
    }
}
