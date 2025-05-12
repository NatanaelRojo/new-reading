<?php

namespace App\Enums\Roles;

use App\Enums\Traits\HasRoleMethods;

enum PanelRoles: string
{
    use HasRoleMethods;
    
    case ADMIN = 'admin';
    case EDITOR = 'editor';
    case USER = 'user';
}