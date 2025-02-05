<?php

namespace Database\Seeders;

use App\Models\API\V1\Genre;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class GenreSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Genre::factory()
            ->count(20)
            ->create();
    }
}
