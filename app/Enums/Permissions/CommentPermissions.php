<?php

namespace App\Enums\Permissions;

use App\Enums\Traits\HasPermissionMethods;

enum CommentPermissions: string
{
    use HasPermissionMethods;

    case VIEW_ANY_CommentS = 'view any Comments';
    case VIEW_ONE_Comment = 'view one Comment';
    case CREATE_CommentS = 'create Comments';
    case EDIT_CommentS = 'edit Comments';
    case DELETE_CommentS = 'delete Comments';
    case RESTORE_CommentS = 'restore Comments';
    case FORCE_DELETE_CommentS = 'force delete Comments';
}
