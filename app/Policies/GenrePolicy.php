<?php

namespace App\Policies;

use App\Enums\Permissions\GenrePermissions;
use App\Enums\Roles\AppRoles;
use App\Models\API\V1\Genre;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class GenrePolicy extends BasePolicy
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
    public function view(User $user, Genre $genre): bool
    {
        return $this->hasCommonViewRoles($user);
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $this->hasCommonCreateRoles($user);
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Genre $genre): bool
    {
        return $this->hasCommonUpdateRoles($user, [
            AppRoles::ADMIN,
            AppRoles::EDITOR,
        ]);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Genre $genre): bool
    {
        return $this->hasCommonDeleteRoles($user);
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Genre $genre): bool
    {
        return $this->hasCommonRestoreRoles($user);
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Genre $genre): bool
    {
        return $this->hasCommonForceDeleteRoles($user);
    }
}
