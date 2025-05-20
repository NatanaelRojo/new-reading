<?php

namespace App\Enums\Permissions;

use App\Enums\Traits\HasPermissionMethods;

enum PostPermissions: string
{
    use HasPermissionMethods;

    case VIEW_ANY_POSTS = 'view any posts';
    case VIEW_ONE_POST = 'view one post';
    case CREATE_POSTS = 'create posts';
    case EDIT_POSTS = 'edit posts';
    case DELETE_POSTS = 'delete posts';
    case RESTORE_POSTS = 'restore posts';
    case FORCE_DELETE_POSTS = 'force delete posts';
}
