<?php

namespace Database\Seeders\Permissions\Web\V1;

use App\Enums\Permissions\BookPermissions;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class BookPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        app()[PermissionRegistrar::class]->forgetCachedPermissions();
        $guardName = 'web';
        $permissions = BookPermissions::cases();

        $this->command->info('Creating Book permissions from App\Permissions\BookPermission...');
        foreach ($permissions as $permission) {
            Permission::firstOrCreate([
                'name' => $permission->getValue(),
                'guard_name' => $guardName,
            ]);
            $this->command->info('Created permission: ' . $permission->getValue());
        }

        $adminRole = Role::findByName('admin', $guardName);
        if ($adminRole) {
            $adminRole->givePermissionTo(BookPermissions::getAllValues());
            $this->command->info("admin role assigned all book permissions.");
        } else {
            $this->command->warn("Role 'Admin' not found. Skipping permission assignment.");
        }

        $editorRole = Role::findByName('editor', $guardName);
        if ($editorRole) {
            $editorRole->givePermissionTo(BookPermissions::getAllValues());
            $this->command->info("editor role assigned all book permissions.");
        } else {
            $this->command->warn("Role 'Editor' not found. Skipping permission assignment.");
        }
    }
}
