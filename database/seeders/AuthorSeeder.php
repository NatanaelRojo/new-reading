<?php

namespace Database\Seeders;

use App\Models\API\V1\Author;
use App\Models\User;
use Database\Factories\AuthorFactory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AuthorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Author::factory()
            ->forUser()
            ->count(30)
            ->create();
    }
}
