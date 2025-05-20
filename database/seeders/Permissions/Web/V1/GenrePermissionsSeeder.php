<?php

namespace Database\Seeders\Permissions\Web\V1;

use App\Enums\Permissions\GenrePermissions;
use App\Enums\Roles\AppRoles;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class GenrePermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        app()[PermissionRegistrar::class]->forgetCachedPermissions();
        $guardName = 'web';
        $permissions = GenrePermissions::cases();

        $this->command->info('Creating Genre permissions...');
        foreach ($permissions as $permission) {
            Permission::firstOrCreate([
                'name' => $permission->getValue(),
                'guard_name' => $guardName,
            ]);
            $this->command->info('Created permission: ' . $permission->getValue());
        }

        $adminRole = Role::findByName(AppRoles::ADMIN->getValue(), $guardName);
        if ($adminRole) {
            $adminRole->givePermissionTo(GenrePermissions::getAllValues());
            $this->command->info("admin role assigned all genre permissions.");
        } else {
            $this->command->warn("Role 'Admin' not found. Skipping permission assignment.");
        }

        $editorRole = Role::findByName(AppRoles::EDITOR->getValue(), $guardName);
        if ($editorRole) {
            $editorRole->givePermissionTo(GenrePermissions::getAllValues());
            $this->command->info("editor role assigned all genre permissions.");
        } else {
            $this->command->warn("Role 'Editor' not found. Skipping permission assignment.");
        }
    }
}
