<?php

namespace Database\Factories\API\V1;

use App\Models\API\V1\Book;
use App\Models\API\V1\Comment;
use App\Models\API\V1\Post;
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
        $commentable = $this->getRandomCommentable();

        return [
            'user_id' => User::inRandomOrder()->first()->id,
            'commentable_id' => $commentable->id,
            'commentable_type' => $commentable::class,
            'body' => fake()->sentence(10),
        ];
    }

    /**
     * Get a random commentable model.
     *
     * @return \App\Models\API\V1\Book|\App\Models\API\V1\Post
     */
    private function getRandomCommentable(): Book|Post
    {
        return fake()->randomElement([
            Book::inRandomOrder()->first(),
            Post::inRandomOrder()->first(),
        ]);
    }
}
