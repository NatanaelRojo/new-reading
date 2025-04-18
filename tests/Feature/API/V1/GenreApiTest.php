<?php

namespace Tests\Feature\API\V1;

use App\Models\API\V1\Genre;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\JsonResponse;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class GenreApiTest extends TestCase
{
    use RefreshDatabase;

    /**
     * The setUp function creates a test user and authenticates with Sanctum for testing purposes.
     */
    protected function setUp(): void
    {
        parent::setUp();

        // ✅ Create a test user and authenticate with Sanctum
        $user = User::factory()->create();
        Sanctum::actingAs($user);
    }


    /**
     * The function tests the creation of a genre by making a new genre instance, sending a POST
     * request to the specified API endpoint, and asserting that the response status is HTTP_CREATED.
     */
    public function test_create_a_genre(): void
    {
        $newGenre = Genre::factory()->make();

        $this->postJson(route('genres.store'), $newGenre->toArray())
        ->assertStatus(JsonResponse::HTTP_CREATED);
    }

    public function test_update_a_genre(): void
    {
        $newGenre = Genre::factory()->create();
        $otherGenre = Genre::factory()->make();

        $this->putJson(route('genres.update', $newGenre->slug), $otherGenre->toArray())
        ->assertStatus(JsonResponse::HTTP_OK)
        ->assertJson($otherGenre->toArray());
    }

    /**
     * This function tests the deletion of a genre through API calls in a PHP environment.
     */
    public function test_delete_a_genre(): void
    {
        $newGenre = Genre::factory()->create();

        $this->deleteJson(route('genres.destroy', $newGenre->slug))
        ->assertStatus(JsonResponse::HTTP_NO_CONTENT);
    }

    /**
     * The function `test_get_a_genre` tests the retrieval of a genre by sending a POST request to
     * create a new genre and then sending a GET request to retrieve the same genre.
     */
    public function test_get_a_genre(): void
    {
        $newGenre = Genre::factory()->create();

        $this->getJson(route('genres.show', $newGenre->slug))
        ->assertStatus(JsonResponse::HTTP_OK)
        ->assertJson([
            'name' => $newGenre['name'],
            'slug' => $newGenre['slug'],
        ]);
    }

    /**
     * The function `test_get_genres` tests the retrieval of genres from an API endpoint in a PHP test
     * case.
     */
    public function test_get_genres(): void
    {
        Genre::factory()
            ->count(5)
            ->create();

        $this->getJson(route('genres.index'))
        ->assertStatus(JsonResponse::HTTP_OK)
        ->assertJsonCount(5, 'data');
    }
}
