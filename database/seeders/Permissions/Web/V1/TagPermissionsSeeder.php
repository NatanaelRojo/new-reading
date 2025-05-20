<?php

namespace Database\Seeders\Permissions\Web\V1;

use App\Enums\Permissions\TagPermissions;
use App\Enums\Roles\AppRoles;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class TagPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        app()[PermissionRegistrar::class]->forgetCachedPermissions();
        $guardName = 'web';
        $permissions = TagPermissions::cases();

        $this->command->info('Creating Tag permissions...');
        foreach ($permissions as $permission) {
            Permission::firstOrCreate([
                'name' => $permission->getValue(),
                'guard_name' => $guardName,
            ]);
            $this->command->info('Created permission: ' . $permission->getValue());
        }

        $adminRole = Role::findByName(AppRoles::ADMIN->getValue(), $guardName);
        if ($adminRole) {
            $adminRole->givePermissionTo(TagPermissions::getAllValues());
            $this->command->info("admin role assigned all tag permissions.");
        } else {
            $this->command->warn("Role 'Admin' not found. Skipping permission assignment.");
        }

        $editorRole = Role::findByName(AppRoles::EDITOR->getValue(), $guardName);
        if ($editorRole) {
            $editorRole->givePermissionTo(TagPermissions::getAllValues());
            $this->command->info("editor role assigned all tag permissions.");
        } else {
            $this->command->warn("Role 'Editor' not found. Skipping permission assignment.");
        }
    }
}
