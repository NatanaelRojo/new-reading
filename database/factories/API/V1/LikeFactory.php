<?php

namespace Database\Factories\API\V1;

use App\Models\API\V1\Review;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Model;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\API\V1\Like>
 */
class LikeFactory extends Factory
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
            'is_dislike' => false,
            'likeable_id' => null,
            'likeable_type' => null,
        ];
    }

    /**
     * Configure the model factory to create a like for a likeable model.
     *
     * @param \Illuminate\Database\Eloquent\Model $model
     * @return LikeFactory
     */
    public function forLikeable(Model $model): static
    {
        return $this->state([
            'likeable_id' => $model->id,
            'likeable_type' => $model::class,
        ]);
    }
}
