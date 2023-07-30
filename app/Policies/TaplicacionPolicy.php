<?php

namespace App\Policies;

use App\Models\Taplicacion;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class TaplicacionPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        //
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Taplicacion $taplicacion)
    {
        return $user->role === 'estudiante';
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user)
    {
        return $user->role === 'estudiante';
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Taplicacion $taplicacion): bool
    {
        //
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Taplicacion $taplicacion): bool
    {
        //
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Taplicacion $taplicacion): bool
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Taplicacion $taplicacion): bool
    {
        //
    }
}
