<?php

namespace Database\Seeders\Permissions\Web\V1;

use App\Enums\Permissions\UserPermissions;
use App\Enums\Roles\AppRoles;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class UserPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        app()[PermissionRegistrar::class]->forgetCachedPermissions();
        $guardName = 'web';
        $permissions = UserPermissions::cases();

        $this->command->info('Creating User permissions from...');
        foreach ($permissions as $permission) {
            Permission::firstOrCreate([
                'name' => $permission->getValue(),
                'guard_name' => $guardName,
            ]);
            $this->command->info('Created permission: ' . $permission->getValue());
        }

        $adminRole = Role::findByName(AppRoles::ADMIN->getValue(), $guardName);
        if ($adminRole) {
            $adminRole->givePermissionTo(UserPermissions::getAllValues());
            $this->command->info("admin role assigned all user permissions.");
        } else {
            $this->command->warn("Role 'Admin' not found. Skipping permission assignment.");
        }

        $editorRole = Role::findByName(AppRoles::EDITOR->getValue(), $guardName);
        if ($editorRole) {
            $editorRole->givePermissionTo(UserPermissions::getAllValues());
            $this->command->info("editor role assigned all user permissions.");
        } else {
            $this->command->warn("Role 'Editor' not found. Skipping permission assignment.");
        }
    }
}
