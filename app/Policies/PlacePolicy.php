<?php

namespace App\Policies;

use App\Models\Place;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class PlacePolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return true;

    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Place $place): bool
    {
        return true;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->role_id == 1;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Place $place): bool
    {
        return $user->id == $place->author_id || $user->role_id == 2;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Place $place): bool
    {
        return $user->id == $place->author_id;
    }

    public function favorite (User $user, Post $post): bool
    {
        return $user->role_id == 1;
    }

}
