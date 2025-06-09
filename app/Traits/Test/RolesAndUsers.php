<?php

namespace App\Traits\Test;

use App\Models\User;
use Spatie\Permission\Models\Role;

trait RolesAndUsers
{
    /**
     * Create a user with roles.
     * @param array<string> $roles Roles to assign to the user.
     * @param string $guard Guard name.
     * @return \App\Models\User User with the given roles.
     */
    protected function createUserWithRoles(array $roles = [], string $guard = 'web'): User
    {
        $user = User::factory()->create();

        foreach ($roles as $role) {
            $currentRole = Role::firstOrCreate([
                'name' => $role,
            ]);
            $user->assignRole($currentRole);
        }

        return $user;
    }
}
