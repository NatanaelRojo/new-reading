<?php

namespace App\Enums\Permissions;

use App\Enums\Traits\HasPermissionMethods;

enum BookPermissions: string
{
    use HasPermissionMethods;

    case VIEW_ANY_BOOKS = 'view any books';
    case VIEW_ONE_BOOK = 'view one book';
    case CREATE_BOOKS = 'create books';
    case EDIT_BOOKS = 'edit books';
    case DELETE_BOOKS = 'delete books';
    case RESTORE_BOOKS = 'restore books';
    case FORCE_DELETE_BOOKS = 'force delete books';
}
