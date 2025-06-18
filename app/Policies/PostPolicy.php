<?php

namespace App\Policies;

use App\Enums\Permissions\PostPermissions;
use App\Enums\Roles\AppRoles;
use App\Models\API\V1\Post;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class PostPolicy extends BasePolicy
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
    public function view(User $user, Post $post): bool
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
        ]);
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Post $post): bool
    {
        if ($user->hasAnyRole([AppRoles::MODERATOR, AppRoles::EDITOR])) {
            return true;
        }

        return $user->hasAnyRole([
                AppRoles::AUTHOR,
                AppRoles::USER
            ]) && $this->isOwner($user, $post);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Post $post): bool
    {
        if ($user->hasRole(AppRoles::MODERATOR)) {
            return true;
        }

        return $this->hasCommonDeleteRoles($user, [
            AppRoles::AUTHOR,
            AppRoles::USER,
        ]) && $this->isOwner($user, $post);
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Post $post): bool
    {
        return $this->hasCommonRestoreRoles($user);
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Post $post): bool
    {
        return $this->hasCommonForceDeleteRoles($user);
    }
}
