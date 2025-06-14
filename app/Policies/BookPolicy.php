<?php

namespace App\Policies;

use App\Enums\Permissions\BookPermissions;
use App\Enums\Roles\AppRoles;
use App\Models\API\V1\Book;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class BookPolicy extends BasePolicy
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
    public function view(User $user, Book $book): bool
    {
        return $this->hasCommonViewRoles($user);
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->hasRole(AppRoles::AUTHOR);
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Book $model): bool
    {
        if ($user->hasRole(AppRoles::EDITOR)) {
            return true;
        }

        return $user->hasRole(AppRoles::AUTHOR) && $user->id === $model->user_id;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Book $model): bool
    {
        return $this->hasCommonDeleteRoles($user);
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Book $model): bool
    {
        return $this->hasCommonRestoreRoles($user);
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Book $model): bool
    {
        return $this->hasCommonForceDeleteRoles($user);
    }
}
