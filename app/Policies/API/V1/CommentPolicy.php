<?php

namespace App\Policies\API\V1;

use App\Enums\Permissions\CommentPermissions;
use App\Models\API\V1\Comment;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class CommentPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo(CommentPermissions::VIEW_ANY_COMMENTS->getValue());
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Comment $comment): bool
    {
        return $user->hasPermissionTo(CommentPermissions::VIEW_ONE_COMMENT->getValue(), $comment);
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->hasPermissionTo(CommentPermissions::CREATE_COMMENTS->getValue());
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Comment $comment): bool
    {
        return $user->hasPermissionTo(CommentPermissions::EDIT_COMMENTS->getValue(), $comment);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Comment $comment): bool
    {
        return $user->hasPermissionTo(CommentPermissions::DELETE_COMMENTS->getValue(), $comment);
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Comment $comment): bool
    {
        return $user->hasPermissionTo(CommentPermissions::RESTORE_COMMENTS->getValue(), $comment);
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Comment $comment): bool
    {
        return $user->hasPermissionTo(CommentPermissions::FORCE_DELETE_COMMENTS->getValue(), $comment);
    }
}
