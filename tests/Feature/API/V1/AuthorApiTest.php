<?php

namespace Tests\Feature\API\V1;

use App\Http\Resources\API\V1\Author\AuthorResource;
use App\Models\API\V1\Author;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\JsonResponse;
use Tests\TestCase;

class AuthorApiTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic feature test example.
     */
    public function test_create_an_author(): void
    {
        $newAuthor = Author::factory()->make();

        $response = $this->postJson('/api/v1/authors', $newAuthor->toArray());

        $response->assertStatus(JsonResponse::HTTP_CREATED);
    }

    /**
     * A basic feature test example.
     */
    public function test_update_an_author(): void
    {
        $newAuthor = Author::factory()->make();
        $otherAuthor = Author::factory()->make();

        $newAuthor = $this->postJson('/api/v1/authors', $newAuthor->toArray());

        $response = $this->putJson('/api/v1/authors/' . $newAuthor['slug'], $otherAuthor->toArray());

        $response->assertStatus(JsonResponse::HTTP_OK)
            ->assertJson([
                'first_name' => $otherAuthor['first_name'],
                'last_name' => $otherAuthor['last_name'],
                'nationality' => $otherAuthor['nationality'],
                'biography' => $otherAuthor['biography'],
                'image_url' => $otherAuthor['image_url'],
            ]);
    }

    public function test_delete_an_author(): void
    {
        $newAuthor = Author::factory()->make();

        $newAuthor = $this->postJson('/api/v1/authors', $newAuthor->toArray());

        $response = $this->deleteJson('/api/v1/authors/' . $newAuthor['slug']);

        $response->assertStatus(JsonResponse::HTTP_NO_CONTENT);
    }

    public function test_get_an_author(): void
    {
        $newAuthor = Author::factory()->make();

        $newAuthor = $this->postJson('/api/v1/authors', $newAuthor->toArray());

        $response = $this->getJson('/api/v1/authors/' . $newAuthor['slug']);

        $response->assertStatus(JsonResponse::HTTP_OK)
            ->assertJson([
                'first_name' => $newAuthor['first_name'],
                'last_name' => $newAuthor['last_name'],
                'nationality' => $newAuthor['nationality'],
                'biography' => $newAuthor['biography'],
                'image_url' => $newAuthor['image_url'],
                'slug' => $newAuthor['slug'],
            ]);
    }

    public function test_get_authors(): void
    {
        $authors = Author::factory()
            ->count(5)
            ->make();

        foreach ($authors as $author) {
            $this->postJson('/api/v1/authors', $author->toArray());
        }

        $response = $this->getJson('/api/v1/authors');

        $response->assertStatus(JsonResponse::HTTP_OK)
            ->assertJsonCount(5);
    }
}
