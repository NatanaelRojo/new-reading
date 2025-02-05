<?php

namespace Database\Seeders;

use App\Models\API\V1\Book;
use App\Models\API\V1\Comment;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CommentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Comment::factory()
            ->count(50)
            ->create();
    }
}
