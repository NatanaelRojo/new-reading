<?php

namespace Database\Seeders;

use App\Models\User;
use Database\Seeders\Permissions\Web\V1\AuthorPermissionSeeder;
use Database\Seeders\Permissions\Web\V1\PermissionsSeeder;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            RoleSeeder::class,
            PermissionsSeeder::class,
            AuthorSeeder::class,
            TagSeeder::class,
            GenreSeeder::class,
            UserSeeder::class,
            CommentSeeder::class,
            ReviewSeeder::class,
            BookSeeder::class,
            PostSeeder::class,
        ]);
    }
}
