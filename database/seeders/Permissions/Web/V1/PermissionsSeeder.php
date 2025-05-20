<?php

namespace Database\Seeders\Permissions\Web\V1;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
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
    }
}
