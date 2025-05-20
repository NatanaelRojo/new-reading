<?php

namespace App\Enums\Roles;

use App\Enums\Traits\HasRoleMethods;

enum AppRoles: string
{
    use HasRoleMethods;

    case ADMIN = 'admin';
    case AUTHOR = 'author';
    case EDITOR = 'editor';
    case MODERATOR = 'moderator';
    case USER = 'user';
}
