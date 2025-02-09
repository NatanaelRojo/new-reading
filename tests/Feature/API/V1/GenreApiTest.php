<?php

namespace Tests\Feature\API\V1;

use App\Models\API\V1\Genre;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\JsonResponse;
use Tests\TestCase;

class GenreApiTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function test_create_a_genre(): void
    {
        $newGenre = Genre::factory()->make();
        $response = $this->postJson('/api/v1/genres', $newGenre->toArray());

        $response
            ->assertStatus(JsonResponse::HTTP_CREATED);
    }

    public function test_update_a_genre(): void
    {
        $newGenre = Genre::factory()->make();
        $otherGenre = Genre::factory()->make();

        $newGenre = $this->postJson('/api/v1/genres', $newGenre->toArray());

        $response = $this->putJson('/api/v1/genres/' . $newGenre['slug'], $otherGenre->toArray());

        $response
            ->assertStatus(JsonResponse::HTTP_OK)
            ->assertJson($otherGenre->toArray());
    }

    public function test_delete_a_genre(): void
    {
        $newGenre = Genre::factory()->make();

        $newGenre = $this->postJson('/api/v1/genres', $newGenre->toArray());

        $response = $this->deleteJson('/api/v1/genres/' . $newGenre['slug']);

        $response
            ->assertStatus(JsonResponse::HTTP_NO_CONTENT);
    }

    public function test_get_a_genre(): void
    {
        $newGenre = Genre::factory()->make();

        $newGenre = $this->postJson('/api/v1/genres', $newGenre->toArray());

        $response = $this->getJson('/api/v1/genres/' . $newGenre['slug']);

        $response
            ->assertStatus(JsonResponse::HTTP_OK)
            ->assertJson([
                'name' => $newGenre['name'],
                'slug' => $newGenre['slug'],
            ]);
    }

    public function test_get_genres(): void
    {
        $genres = Genre::factory()
            ->count(5)
            ->make();

        foreach ($genres as $genre) {
            $this->postJson('/api/v1/genres', $genre->toArray());
        }

        $response = $this->getJson('/api/v1/genres');

        $response
            ->assertStatus(JsonResponse::HTTP_OK)
            ->assertJsonCount(8);
    }
}
