<?php

namespace App\Enums\Permissions;

use App\Enums\Traits\HasPermissionMethods;

enum AuthorPermissions: string
{
    use HasPermissionMethods;

    case VIEW_ANY_AUTHORS = 'view any authors';
    case CREATE_AUTHORS = 'create authors';
    case EDIT_AUTHORS = 'edit authors';
    case DELETE_AUTHORS = 'delete authors';
}
