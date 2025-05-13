<?php

namespace App\Enums\Permissions;

use App\Enums\Traits\HasPermissionMethods;

enum ReviewPermissions: string
{
    use HasPermissionMethods;

    case VIEW_ANY_REVIEWS = 'view any reviews';
    case VIEW_ONE_REVIEW = 'view one review';
    case CREATE_REVIEWS = 'create reviews';
    case EDIT_REVIEWS = 'edit reviews';
    case DELETE_REVIEWS = 'delete reviews';
    case RESTORE_REVIEWS = 'restore reviews';
    case FORCE_DELETE_REVIEWS = 'force delete reviews';
}
