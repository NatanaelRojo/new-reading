<?php

namespace App\Policies;

use App\Enums\Permissions\CommentPermissions;
use App\Enums\Roles\AppRoles;
use App\Models\API\V1\Comment;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class CommentPolicy extends BasePolicy
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
    public function view(User $user, Comment $comment): bool
    {
        return $this->hasCommonViewRoles($user);
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->hasAnyRole([
            AppRoles::AUTHOR,
            AppRoles::USER,
            AppRoles::MODERATOR,
        ]);
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Comment $comment): bool
    {
        return $this->isOwner($user, $comment);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Comment $comment): bool
    {
        if ($user->hasRole(AppRoles::MODERATOR)) {
            return true;
        }

        return $user->hasRole(AppRoles::USER) && $this->isOwner($user, $comment);
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Comment $comment): bool
    {
        return $this->hasCommonRestoreRoles($user);
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Comment $comment): bool
    {
        return $this->hasCommonForceDeleteRoles($user);
    }
}
