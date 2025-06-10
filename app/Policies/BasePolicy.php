<?php

namespace App\Policies;

use App\Enums\Roles\AppRoles;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;

abstract class BasePolicy
{
    /**
     * Determines if the user has any of the common roles allowed for general viewing.
     *
     * @return bool
     */
    protected function hasCommonViewAnyRoles(User $user): bool
    {
        return $user->hasAnyRole([
            AppRoles::EDITOR,
            AppRoles::AUTHOR,
            AppRoles::MODERATOR,
            AppRoles::USER,
        ]);
    }

    /**
     * Determines if the user has any of the common roles allowed for viewing a specific model.
     *
     * @return bool
     */
    protected function hasCommonViewRoles(User $user): bool
    {
        return $user->hasAnyRole([
            AppRoles::EDITOR,
            AppRoles::AUTHOR,
            AppRoles::MODERATOR,
            AppRoles::USER,
        ]);
    }

    /**
     * Determines if the user has common roles allowed for creating.
     *
     * @return bool
     */
    protected function hasCommonCreateRoles(User $user): bool
    {
        return $user->hasAnyRole([
            AppRoles::ADMIN,
        ]);
    }

    /**
     * Determines if the user has common roles allowed for updating.
     *
     * @return bool
     */
    protected function hasCommonUpdateRoles(User $user): bool
    {
        return $user->hasAnyRole([
            AppRoles::ADMIN,
            AppRoles::EDITOR,
            AppRoles::AUTHOR,
        ]);
    }

    /**
     * Determines if the user has common roles allowed for deleting.
     *
     * @param \App\Models\User $user The authenticated user.
     * @return bool
     */
    protected function hasCommonDeleteRoles(User $user): bool
    {
        return $user->hasAnyRole([
            AppRoles::ADMIN,
            AppRoles::MODERATOR,
        ]);
    }

    /**
     * Determines if the user has common roles allowed for restoring.
     *
     * @param \App\Models\User $user The authenticated user.
     * @return bool
     */
    protected function hasCommonRestoreRoles(User $user): bool
    {
        return $user->hasAnyRole([
            AppRoles::ADMIN,
            AppRoles::MODERATOR,
        ]);
    }

    /**
     * Determines if the user has common roles allowed for force deleting.
     *
     * @param \App\Models\User $user The authenticated user.
     * @return bool
     */
    protected function hasCommonForceDeleteRoles(User $user): bool
    {
        return $user->hasAnyRole([
            AppRoles::ADMIN,
            // AppRoles::MODERATOR,
        ]);
    }

    /**
     * Determine if a user is the owner of a given model.
     * This is a common helper for update/delete policies if models are owned by users.
     *
     * @param User $user The authenticated user.
     * @param Model $model The model to check ownership for.
     * @return bool
     */
    protected function isOwner(User $user, Model $model): bool
    {
        return property_exists($model, 'user_id') && $user->id === $model->user_id;
    }
}
