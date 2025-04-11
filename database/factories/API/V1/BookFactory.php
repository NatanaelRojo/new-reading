<?php

namespace Database\Factories\API\V1;

use App\Models\API\V1\Author;
use App\Models\API\V1\Book;
use App\Models\API\V1\Genre;
use App\Models\API\V1\Review;
use App\Models\API\V1\Tag;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\API\V1\Book>
 */
class BookFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => fake()->sentence(),
            'synopsis' => fake()->text(),
            'isbn' => fake()->isbn13(),
            'pages_amount' => fake()->numberBetween(1, 1000),
            'chapters_amount' => fake()->numberBetween(1, 50),
            'published_at' => fake()->date(),
            'image_url' => fake()->imageUrl(),
        ];
    }

    public function configure(): static
    {
        return $this->afterCreating(function (Book $book) {
            $book->authors()
                ->attach(
                    Author::inRandomOrder()->take(rand(1, 10))
                    ->pluck('id')
                );
            $book->genres()
                ->attach(
                    Genre::inRandomOrder()->take(rand(1, 10))
                    ->pluck('id')
                );
            Review::factory()
                ->count(rand(1, 10))
                ->create(['book_id' => $book->id]);
            $book->users()
                ->attach(
                    User::inRandomOrder()->take(rand(1, 10))
                    ->pluck('id')
                );
            foreach ($book->users as $user) {
                $user->pivot->tag_id = Tag::inRandomOrder()->take(1)->first()->id;
                $user->pivot->pages_read = fake()->numberBetween(1, $book->pages_amount);
                $user->pivot->save();
            }
        });
    }
}
