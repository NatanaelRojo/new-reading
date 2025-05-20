<?php

namespace App\Policies;

use App\Enums\Permissions\BookPermissions;
use App\Models\API\V1\Book;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class BookPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo(BookPermissions::VIEW_ANY_BOOKS);
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Book $model): bool
    {
        return $user->hasPermissionTo(BookPermissions::VIEW_ONE_BOOK);
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->hasPermissionTo(BookPermissions::CREATE_BOOKS);
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Book $model): bool
    {
        return $user->hasPermissionTo(BookPermissions::EDIT_BOOKS);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Book $model): bool
    {
        return $user->hasPermissionTo(BookPermissions::DELETE_BOOKS);
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Book $model): bool
    {
        return $user->hasPermissionTo(BookPermissions::RESTORE_BOOKS);
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Book $model): bool
    {
        return $user->hasPermissionTo(BookPermissions::FORCE_DELETE_BOOKS);
    }
}
