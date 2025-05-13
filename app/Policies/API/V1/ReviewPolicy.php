<?php

namespace App\Policies\API\V1;

use App\Enums\Permissions\ReviewPermissions;
use App\Models\API\V1\Review;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class ReviewPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo(ReviewPermissions::VIEW_ANY_REVIEWS);
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Review $review): bool
    {
        return $user->hasPermissionTo(ReviewPermissions::VIEW_ONE_REVIEW, $review);
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->hasPermissionTo(ReviewPermissions::CREATE_REVIEWS);
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Review $review): bool
    {
        return $user->hasPermissionTo(ReviewPermissions::EDIT_REVIEWS, $review);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Review $review): bool
    {
        return $user->hasPermissionTo(ReviewPermissions::DELETE_REVIEWS, $review);
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Review $review): bool
    {
        return $user->hasPermissionTo(ReviewPermissions::RESTORE_REVIEWS, $review);
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Review $review): bool
    {
        return $user->hasPermissionTo(ReviewPermissions::FORCE_DELETE_REVIEWS, $review);
    }
}
