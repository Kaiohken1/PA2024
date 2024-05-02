<?php

namespace App\Policies;

use App\Models\Appartement;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class AppartementPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user)
    {
        //
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Appartement $appartement)
    {
        //
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user)
    {
        //
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Appartement $appartement)
    {
        return $user->id === $appartement->user_id;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Appartement $appartement)
    {
        return $user->id === $appartement->user_id;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Appartement $appartement)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Appartement $appartement)
    {
        //
    }
}
