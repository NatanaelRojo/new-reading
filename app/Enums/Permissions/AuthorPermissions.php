<?php

namespace App\Enums\Permissions;

use App\Enums\Traits\HasPermissionMethods;

enum AuthorPermissions: string
{
    use HasPermissionMethods;

    case VIEW_ANY_AUTHORS = 'view any authors';
    case VIEW_ONE_AUTHOR = 'view one author';
    case CREATE_AUTHORS = 'create authors';
    case EDIT_AUTHORS = 'edit authors';
    case DELETE_AUTHORS = 'delete authors';
    case RESTORE_AUTHORS = 'restore authors';
    case FORCE_DELETE_AUTHORS = 'force delete authors';
}
