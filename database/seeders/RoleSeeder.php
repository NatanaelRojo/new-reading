<?php

namespace Database\Seeders;

use App\Enums\Roles\AppRoles;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
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
        $panelRoles = AppRoles::cases();
        $guardName = 'web';

        foreach ($panelRoles as $role) {
            Role::firstOrCreate([
                'name' => $role->getValue(),
                'guard_name' => $guardName,
            ]);
            $this->command->info('Created role: ' . $role->getValue());
        }

        $this->command->info('All core roles have been processed successfully.');

        $adminUser = User::factory()->create([
                'name' => config('admin_user.name'),
                'email' => config('admin_user.email'),
                'password' => Hash::make(config('admin_user.password')),
        ]);

        $adminUser->assignRole(AppRoles::ADMIN->getValue());
    }
}
