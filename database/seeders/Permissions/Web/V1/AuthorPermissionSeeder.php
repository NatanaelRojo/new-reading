<?php

namespace Database\Seeders\Permissions\Web\V1;

use App\Enums\Permissions\AuthorPermissions;
use App\Enums\Roles\PanelRoles;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class AuthorPermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        app()[PermissionRegistrar::class]->forgetCachedPermissions();
        $guardName = 'web';
        $permissions = AuthorPermissions::cases();

        $this->command->info('Creating Author permissions from App\Permissions\AuthorPermission...');
        foreach ($permissions as $permission) {
            Permission::firstOrCreate([
                'name' => $permission->getValue(),
                'guard_name' => $guardName,
            ]);
            $this->command->info('Created permission: ' . $permission->getValue());
        }

        $adminRole = Role::findByName(PanelRoles::ADMIN->getValue(), $guardName);
        if ($adminRole) {
            $adminRole->givePermissionTo(AuthorPermissions::getAllValues());
            $this->command->info("admin role assigned all author permissions.");
        } else {
            $this->command->warn("Role 'Admin' not found. Skipping permission assignment.");
        }

        $editorRole = Role::findByName(PanelRoles::EDITOR->getValue(), $guardName);
        if ($editorRole) {
            $editorRole->givePermissionTo(AuthorPermissions::getAllValues());
            $this->command->info("editor role assigned all author permissions.");
        } else {
            $this->command->warn("Role 'Editor' not found. Skipping permission assignment.");
        }
    }
}
