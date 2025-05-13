<?php

namespace App\Enums\Permissions;

use App\Enums\Traits\HasPermissionMethods;

enum UserPermissions: string
{
    use HasPermissionMethods;

    case VIEW_ANY_USERS = 'view any users';
    case VIEW_ONE_USER = 'view one user';
    case CREATE_USERS = 'create users';
    case EDIT_USERS = 'edit users';
    case DELETE_USERS = 'delete users';
    case RESTORE_USERS = 'restore users';
    case FORCE_DELETE_USERS = 'force delete users';
}
