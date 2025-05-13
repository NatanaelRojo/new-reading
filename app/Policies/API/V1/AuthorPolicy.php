<?php

namespace App\Policies\API\V1;

use App\Enums\Permissions\AuthorPermissions;
use App\Models\API\V1\Author;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class AuthorPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo(AuthorPermissions::VIEW_ANY_AUTHORS);
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Author $model): bool
    {
        return $user->hasPermissionTo(AuthorPermissions::VIEW_ONE_AUTHOR->getValue());
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->hasPermissionTo(AuthorPermissions::CREATE_AUTHORS);
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Author $model): bool
    {
        return $user->hasPermissionTo(AuthorPermissions::EDIT_AUTHORS);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Author $model): bool
    {
        return $user->hasPermissionTo(AuthorPermissions::DELETE_AUTHORS);
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Author $model): bool
    {
        return $user->hasPermissionTo(AuthorPermissions::RESTORE_AUTHORS);
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Author $model): bool
    {
        return $user->hasPermissionTo(AuthorPermissions::FORCE_DELETE_AUTHORS);
    }
}
