<?php

namespace App\Policies\API\V1;

use App\Enums\Permissions\TagPermissions;
use App\Models\API\V1\Tag;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class TagPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo(TagPermissions::VIEW_ANY_TAGS->getValue());
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Tag $tag): bool
    {
        return $user->hasPermissionTo(TagPermissions::VIEW_ONE_TAG->getValue());
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->hasPermissionTo(TagPermissions::CREATE_TAGS->getValue());
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Tag $tag): bool
    {
        return $user->hasPermissionTo(TagPermissions::EDIT_TAGS);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Tag $tag): bool
    {
        return $user->hasPermissionTo(TagPermissions::DELETE_TAGS->getValue());
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Tag $tag): bool
    {
        return $user->hasPermissionTo(TagPermissions::RESTORE_TAGS->getValue());
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Tag $tag): bool
    {
        return $user->hasPermissionTo(TagPermissions::FORCE_DELETE_TAGS->getValue());
    }
}
