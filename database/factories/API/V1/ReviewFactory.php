<?php

namespace Database\Factories\API\V1;

use App\Models\API\V1\Book;
use App\Models\API\V1\Like;
use App\Models\API\V1\Review;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\API\V1\Review>
 */
class ReviewFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::inRandomOrder()->first()->id,
            'book_id' => Book::inRandomOrder()->first(),
            'comment' => fake()->text(),
            'rating' => fake()->numberBetween(1, 5),
        ];
    }

    /**
     * Configure the model factory to create a review for a book.
     *
     * @return ReviewFactory
     */
    public function configure(): static
    {
        return $this->afterCreating(function (Review $review) {
            Like::factory()
                    ->count(rand(1, 5))
                    ->forLikeable($review)
                    ->create();
            $review->updateLikeCounters();
        });
    }
}
