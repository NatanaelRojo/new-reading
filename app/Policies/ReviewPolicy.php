<?php

namespace App\Policies;

use App\Enums\Permissions\ReviewPermissions;
use App\Enums\Roles\AppRoles;
use App\Models\API\V1\Review;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class ReviewPolicy extends BasePolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $this->hasCommonViewAnyRoles($user);
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Review $review): bool
    {
        return $this->hasCommonViewRoles($user);
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $this->hasCommonCreateRoles($user, [
            AppRoles::AUTHOR,
            AppRoles::USER,
        ]);
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Review $review): bool
    {
        if ($user->hasRole(AppRoles::MODERATOR)) {
            return true;
        }

        return $this->hasCommonUpdateRoles($user, [AppRoles::AUTHOR, AppRoles::USER]) && $this->isOwner($user, $review);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Review $review): bool
    {
        if ($user->hasRole(AppRoles::MODERATOR)) {
            return true;
        }

        return $this->hasCommonDeleteRoles($user, [
            AppRoles::AUTHOR,
            AppRoles::USER,
        ]) && $this->isOwner($user, $review);
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Review $review): bool
    {
        return $this->hasCommonRestoreRoles($user);
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Review $review): bool
    {
        return $this->hasCommonForceDeleteRoles($user);
    }
}
