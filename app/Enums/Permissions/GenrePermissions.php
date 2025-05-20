<?php

namespace App\Enums\Permissions;

use App\Enums\Traits\HasPermissionMethods;

enum GenrePermissions: string
{
    use HasPermissionMethods;

    case VIEW_ANY_GENRES = 'view any genres';
    case VIEW_ONE_GENRE = 'view one genre';
    case CREATE_GENRES = 'create genres';
    case EDIT_GENRES = 'edit genres';
    case DELETE_GENRES = 'delete genres';
    case RESTORE_GENRES = 'restore genres';
    case FORCE_DELETE_GENRES = 'force delete genres';
}
