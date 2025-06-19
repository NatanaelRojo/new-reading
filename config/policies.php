<?php

use App\Enums\Roles\AppRoles;

return [
    'default_roles' => [
        'view_any' => [
            AppRoles::EDITOR->value,
            AppRoles::AUTHOR->value,
            AppRoles::MODERATOR->value,
            AppRoles::USER->value,
        ],
        'view' => [
            AppRoles::EDITOR->value,
            AppRoles::AUTHOR->value,
            AppRoles::MODERATOR->value,
            AppRoles::USER->value,
        ],
        'create' => [
            AppRoles::ADMIN->value,
        ],
        'update' => [
            AppRoles::ADMIN->value,
            AppRoles::EDITOR->value,
            AppRoles::AUTHOR->value, // Assuming author can update their own content
        ],
        'delete' => [
            AppRoles::ADMIN->value,
            AppRoles::MODERATOR->value,
        ],
        'restore' => [
            AppRoles::ADMIN->value,
            AppRoles::MODERATOR->value,
        ],
        'force_delete' => [
            AppRoles::ADMIN->value,
        ],
    ],
    'panel_roles' => [
        AppRoles::ADMIN,
        AppRoles::EDITOR,
        AppRoles::MODERATOR,
    ],
];
