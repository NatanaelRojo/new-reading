<?php

namespace Database\Seeders;

use App\Models\API\V1\Book;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class BookSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Book::factory()
            ->count(10)
            ->create();
    }
}
