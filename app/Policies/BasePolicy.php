<?php

namespace App\Policies;

use App\Enums\Roles\AppRoles;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;

abstract class BasePolicy
{
    /**
     * Determine if the user has any of the common roles allowed for general viewing.
     * If specific roles are provided, it checks against those; otherwise, it checks against a default set defined in config.
     *
     * @param \App\Models\User $user The authenticated user.
     * @param array $roles An optional array of roles to check against. If empty, a default set of roles is used from config.
     * @return bool
     */
    protected function hasCommonViewAnyRoles(User $user, array $roles = []): bool
    {
        $rolesToCheck = !empty($roles)
            ? $roles :
            config('policies.default_roles.view_any', []);

        return $user->hasAnyRole($rolesToCheck);
    }

    /**
     * Determine if the user has any of the common roles allowed for viewing a specific model.
     * If specific roles are provided, it checks against those; otherwise, it checks against a default set defined in config.
     *
     * @param \App\Models\User $user The authenticated user.
     * @param array $roles An optional array of roles to check against. If empty, a default set of roles is used from config.
     * @return bool
     */
    protected function hasCommonViewRoles(User $user, array $roles = []): bool
    {
        $rolesToCheck = !empty($roles) ?
            $roles :
            config('policies.default_roles.view', []);

        return $user->hasAnyRole($rolesToCheck);
    }

    /**
     * Determine if the user has common roles allowed for creating.
     * If specific roles are provided, it checks against those; otherwise, it checks against a default set defined in config.
     *
     * @param \App\Models\User $user The authenticated user.
     * @param array $roles An optional array of roles to check against. If empty, a default set of roles is used from config.
     * @return bool
     */
    protected function hasCommonCreateRoles(User $user, array $roles = []): bool
    {
        $rolesToCheck = !empty($roles) ?
            $roles :
            config('policies.default_roles.create', []);

        return $user->hasAnyRole($rolesToCheck);
    }

    /**
     * Determine if the user has common roles allowed for updating.
     * If specific roles are provided, it checks against those; otherwise, it checks against a default set defined in config.
     *
     * @param \App\Models\User $user The authenticated user.
     * @param array $roles An optional array of roles to check against. If empty, a default set of roles is used from config.
     * @return bool
     */
    protected function hasCommonUpdateRoles(User $user, array $roles = []): bool
    {
        $rolesToCheck = !empty($roles) ?
            $roles :
            config('policies.default_roles.update', []);

        return $user->hasAnyRole($rolesToCheck);
    }

    /**
     * Determine if the user has common roles allowed for deleting.
     * If specific roles are provided, it checks against those; otherwise, it checks against a default set defined in config.
     *
     * @param \App\Models\User $user The authenticated user.
     * @param array $roles An optional array of roles to check against. If empty, a default set of roles is used from config.
     * @return bool
     */
    protected function hasCommonDeleteRoles(User $user, array $roles = []): bool
    {
        $rolesToCheck = !empty($roles) ?
            $roles :
            config('policies.default_roles.delete', []);

        return $user->hasAnyRole($rolesToCheck);
    }

    /**
     * Determine if the user has common roles allowed for restoring.
     * If specific roles are provided, it checks against those; otherwise, it checks against a default set defined in config.
     *
     * @param \App\Models\User $user The authenticated user.
     * @param array $roles An optional array of roles to check against. If empty, a default set of roles is used from config.
     * @return bool
     */
    protected function hasCommonRestoreRoles(User $user, array $roles = []): bool
    {
        $rolesToCheck = !empty($roles) ?
            $roles :
            config('policies.default_roles.restore', []);

        return $user->hasAnyRole($rolesToCheck);
    }

    /**
     * Determine if the user has common roles allowed for force deleting.
     * If specific roles are provided, it checks against those; otherwise, it checks against a default set defined in config.
     *
     * @param \App\Models\User $user The authenticated user.
     * @param array $roles An optional array of roles to check against. If empty, a default set of roles is used from config.
     * @return bool
     */
    protected function hasCommonForceDeleteRoles(User $user, array $roles = []): bool
    {
        $rolesToCheck = !empty($roles) ?
            $roles :
            config('policies.default_roles.force_delete', []);

        return $user->hasAnyRole($rolesToCheck);
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
        return $user->id === $model->user_id;
    }
}
