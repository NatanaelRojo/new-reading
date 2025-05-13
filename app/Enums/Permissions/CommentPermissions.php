<?php

namespace App\Enums\Permissions;

use App\Enums\Traits\HasPermissionMethods;

enum CommentPermissions: string
{
    use HasPermissionMethods;

    case VIEW_ANY_COMMENTS = 'view any comments';
    case VIEW_ONE_COMMENT = 'view one comment';
    case CREATE_COMMENTS = 'create comments';
    case EDIT_COMMENTS = 'edit comments';
    case DELETE_COMMENTS = 'delete comments';
    case RESTORE_COMMENTS = 'restore comments';
    case FORCE_DELETE_COMMENTS = 'force delete comments';
}
