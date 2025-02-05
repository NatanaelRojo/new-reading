<?php

namespace Database\Factories\API\V1;

use App\Models\API\V1\Book;
use App\Models\API\V1\Comment;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\API\V1\Comment>
 */
class CommentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'book_id' => Book::factory(),
            'body' => fake()->text(),
        ];
    }

    // public function configure(): static
    // {
    //     return $this->afterCreating(function (Comment $comment) {
    //         $comment->book_id = Book::inRandomOrder()
    //             ->first()
    //             ->id;
    //         $comment->user_id = User::inRandomOrder()
    //             ->first()
    //             ->id;
    //     });
    // }
}
