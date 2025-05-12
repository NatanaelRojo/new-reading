<?php

namespace Database\Seeders;

use App\Enums\Roles\PanelRoles;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        app()[PermissionRegistrar::class]->forgetCachedPermissions();
        $panelRoles = PanelRoles::cases();
        $guardName = 'web';

        foreach ($panelRoles as $role) {
            Role::firstOrCreate([
                'name' => $role->getValue(),
                'guard_name' => $guardName,
            ]);
            $this->command->info('Created role: ' . $role->getValue());
        }

        $this->command->info('All core roles have been processed successfully.');
    }
}
