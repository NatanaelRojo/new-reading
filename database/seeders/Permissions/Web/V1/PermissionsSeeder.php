<?php

namespace Database\Seeders\Permissions\Web\V1;

use App\Enums\Roles\AppRoles;
use App\Services\API\V1\RolePermissionAssignerService;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class PermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $rolesToAssign = [
            AppRoles::ADMIN->getValue() => [RolePermissionAssignerService::class, 'forAdmin'],
            AppRoles::AUTHOR->getValue() => [RolePermissionAssignerService::class, 'forAuthor'],
            AppRoles::EDITOR->getValue() => [RolePermissionAssignerService::class, 'forEditor'],
            AppRoles::MODERATOR->getValue() => [RolePermissionAssignerService::class, 'forModerator'],
            AppRoles::USER->getValue() => [RolePermissionAssignerService::class, 'forUser'],
        ];
        $guardName = 'web';
        $this->call([
            AuthorPermissionSeeder::class,
            BookPermissionsSeeder::class,
            CommentPermissionsSeeder::class,
            GenrePermissionsSeeder::class,
            PostPermissionsSeeder::class,
            ReviewPermissionsSeeder::class,
            TagPermissionsSeeder::class,
            UserPermissionsSeeder::class,
        ]);

        foreach ($rolesToAssign as $role => $method) {
            $this->assignPermissionsToRole($role, $method, $guardName);
        }

        app(\Spatie\Permission\PermissionRegistrar::class)->forgetCachedPermissions();
        $this->command->info('Roles and permissions seeding completed.');
    }

    /**
     * Assign permissions to a role.
     *
     * @param string $role
     * @param callable $method
     * @param string $guard
     * @return void
     */
    private function assignPermissionsToRole(string $role, callable $method, string $guard): void
    {
        $role = Role::findByName($role, $guard);
        if ($role) {
            $permissions = call_user_func($method);
            $role->givePermissionTo($permissions);
            $this->command->info("Permissions for role '{$role->name}' were assigned.");
        } else {
            $this->command->warn("Role '{$role->name}' not found. Skipping permission assignment.");
        }
    }
}
