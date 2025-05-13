<?php

namespace App\Policies\API\V1;

use App\Enums\Permissions\PostPermissions;
use App\Models\API\V1\Post;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class PostPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo(PostPermissions::VIEW_ANY_POSTS);
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Post $post): bool
    {
        return $user->hasPermissionTo(PostPermissions::VIEW_ONE_POST);
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->hasPermissionTo(PostPermissions::CREATE_POSTS);
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Post $post): bool
    {
        return $user->hasPermissionTo(PostPermissions::EDIT_POSTS);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Post $post): bool
    {
        return $user->hasPermissionTo(PostPermissions::DELETE_POSTS);
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Post $post): bool
    {
        return $user->hasPermissionTo(PostPermissions::RESTORE_POSTS);
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Post $post): bool
    {
        return $user->hasPermissionTo(PostPermissions::FORCE_DELETE_POSTS);
    }
}
