<?php

namespace Tests\Feature\API\V1;

use App\Models\API\V1\Author;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\JsonResponse;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class AuthorApiTest extends TestCase
{
    use RefreshDatabase;

    private User $user;

    /**
     * Set up a fresh user and authenticate with Sanctum before each test.
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();
        Sanctum::actingAs($this->user);
    }

    /**
     * Test that a new author can be created via POST /authors.
     *
     * @return void
     */
    public function test_create_an_author(): void
    {
        $newAuthor = Author::factory()->make();

        $this->postJson(route('authors.store'), $newAuthor->toArray())
            ->assertStatus(JsonResponse::HTTP_CREATED);
    }

    /**
     * Test that an existing author can be updated via PUT /authors/{slug}.
     *
     * @return void
     */
    public function test_update_an_author(): void
    {
        $newAuthor = Author::factory()->create();
        $otherAuthor = Author::factory()->make();

        $this->putJson(route('authors.update', $newAuthor->slug), $otherAuthor->toArray())
            ->assertStatus(JsonResponse::HTTP_OK)
            ->assertJson($otherAuthor->toArray());
    }

    /**
     * Test that an existing author can be deleted via DELETE /authors/{slug}.
     *
     * @return void
     */
    public function test_delete_an_author(): void
    {
        $newAuthor = Author::factory()->create();

        $this->deleteJson(route('authors.destroy', $newAuthor->slug))
            ->assertStatus(JsonResponse::HTTP_NO_CONTENT);
    }

    /**
     * Test that a single author can be retrieved via GET /authors/{slug}.
     *
     * @return void
     */
    public function test_get_an_author(): void
    {
        $newAuthor = Author::factory()->create();

        $this->getJson(route('authors.show', $newAuthor->slug))
            ->assertStatus(JsonResponse::HTTP_OK)
            ->assertJson([
                'first_name' => $newAuthor->first_name,
                'last_name' => $newAuthor->last_name,
                'nationality' => $newAuthor->nationality,
                'biography' => $newAuthor->biography,
                'image_url' => $newAuthor->image_url,
                'slug' => $newAuthor->slug,
            ]);
    }

    /**
     * Test that a paginated list of authors is returned via GET /authors.
     *
     * @return void
     */
    public function test_get_authors(): void
    {
        Author::factory()->count(5)->create();

        $this->getJson(route('authors.index'))
            ->assertStatus(JsonResponse::HTTP_OK)
            ->assertJsonCount(5, 'data');
    }
}
