<?php

namespace Database\Factories\API\V1;

use App\Models\API\V1\Author;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\API\V1\Author>
 */
class AuthorFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'first_name' => fake()->firstName(),
            'last_name' => fake()->lastName(),
            'nationality' => fake()->country(),
            'biography' => fake()->text(),
            'image_url' => fake()->imageUrl(),
        ];
    }

    /**
     * Configure the Author factory to associate a newly created User with the Author.
     *
     * This method uses the afterCreating callback to create a new User instance
     * and updates the Author's user_id field with the created User's ID.
     *
     * @param User $user The User instance to associate with the Author.
     * @return static
     */
    public function forUser(?User $user = null): static
    {
        return $this->afterCreating(function (Author $author) use ($user): void {
            if (!$user) {
                $user = User::factory()->create();
            }

            $author->update(['user_id' => $user->id]);
        });
    }
}
