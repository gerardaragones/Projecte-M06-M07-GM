<?php

namespace App\Policies;

use App\Models\Review;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class ReviewPolicy
{
    /**
     * Determine whether the user can create reviews.
     */
    public function create(User $user): bool
    {
        // Pueden crear revisiones si no son el autor del lugar
        return !$user->isPublisher();
    }

    /**
     * Determine whether the user can update the review.
     */
    public function update(User $user, Review $review): bool
    {
        // Solo el autor de la revisión puede actualizarla
        return $user->id === $review->user_id;
    }

    /**
     * Determine whether the user can delete the review.
     */
    public function delete(User $user, Review $review): bool
    {
        // Solo el autor de la revisión puede eliminarla
        return $user->id === $review->user_id;
    }
}
