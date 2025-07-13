<?php

namespace Database\Factories\API\V1;

use App\Models\API\V1\Book;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\API\V1\Post>
 */
class PostFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::inRandomOrder()->first(),
            'book_id' => Book::inRandomOrder()->first,
            'body' => fake()->text(),
            'progress' => fake()->numberBetween(0, 100),
        ];
    }
}
