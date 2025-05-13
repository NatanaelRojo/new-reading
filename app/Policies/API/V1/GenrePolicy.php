<?php

namespace App\Policies\API\V1;

use App\Enums\Permissions\GenrePermissions;
use App\Models\API\V1\Genre;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class GenrePolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo(GenrePermissions::VIEW_ANY_GENRES);
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Genre $genre): bool
    {
        return $user->hasPermissionTo(GenrePermissions::VIEW_ONE_GENRE);
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->hasPermissionTo(GenrePermissions::CREATE_GENRES);
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Genre $genre): bool
    {
        return $user->hasPermissionTo(GenrePermissions::EDIT_GENRES);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Genre $genre): bool
    {
        return $user->hasPermissionTo(GenrePermissions::DELETE_GENRES);
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Genre $genre): bool
    {
        return $user->hasPermissionTo(GenrePermissions::RESTORE_GENRES);
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Genre $genre): bool
    {
        return $user->hasPermissionTo(GenrePermissions::FORCE_DELETE_GENRES);
    }
}
