<?php

namespace App\Enums\Permissions;

use App\Enums\Traits\HasPermissionMethods;

enum TagPermissions: string
{
    use HasPermissionMethods;

    case VIEW_ANY_TAGS = 'view any tags';
    case VIEW_ONE_TAG = 'view one tag';
    case CREATE_TAGS = 'create tags';
    case EDIT_TAGS = 'edit tags';
    case DELETE_TAGS = 'delete tags';
    case RESTORE_TAGS = 'restore tags';
    case FORCE_DELETE_TAGS = 'force delete tags';
}
