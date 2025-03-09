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
            'user_id' => User::factory(),
            'commentable_id' => $commentable->id,
            'commentable_type' => $commentable::class,
            'body' => fake()->sentence(10),
        ];
    }

    private function getRandomCommentable(): Book|Post
    {
        return fake()->randomElement([
            Book::factory()->create(),
            Post::factory()->create(),
        ]);
    }
}
