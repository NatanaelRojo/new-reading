<?php

namespace Tests\Feature\API\V1;

use App\Models\API\V1\Tag;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\JsonResponse;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class TagApiTest extends TestCase
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
     * The function `test_create_a_tag` tests the creation of a new tag by sending a POST request to
     * the specified API endpoint.
     */
    public function test_create_a_tag(): void
    {
        $newTag = Tag::factory()->make();

        $this->postJson(route('tags.store'), $newTag->toArray())
            ->assertStatus(JsonResponse::HTTP_CREATED);
    }

    /**
     * The function `test_update_a_tag` tests updating a tag by sending a PUT request to the specified
     * API endpoint.
     */
    public function test_update_a_tag(): void
    {
        $newTag = Tag::factory()->create();
        $otherTag = Tag::factory()->make();

        $this->putJson(route('tags.update', $newTag->slug), $otherTag->toArray())
        ->assertStatus(JsonResponse::HTTP_OK)
        ->assertJson($otherTag->toArray());
    }

    /**
     * The function `test_delete_a_tag` tests the deletion of a tag through API calls in a PHP
     * environment.
     */
    public function test_delete_a_tag(): void
    {
        $newTag = Tag::factory()->create();

        $this->deleteJson(route('tags.destroy', $newTag->slug))
        ->assertStatus(JsonResponse::HTTP_NO_CONTENT);

        $this->assertDatabaseMissing('tags', $newTag->toArray());
    }

    /**
     * The function `test_get_a_tag` tests the retrieval of a specific tag using API endpoints in a PHP
     * test case.
     */
    public function test_get_a_tag(): void
    {
        $newTag = Tag::factory()->create();

        $this->getJson(route('tags.show', $newTag->slug))
            ->assertStatus(JsonResponse::HTTP_OK)
            ->assertJson([
                'name' => $newTag['name'],
                'slug' => $newTag['slug'],
        ]);
    }

    /**
     * The function `test_get_tags` tests the retrieval of a list of tags through API calls in a PHP
     * environment.
     * @return void
     */
    public function test_get_tags(): void
    {
        Tag::factory()
            ->count(5)
            ->create();

        $this->getJson(route('tags.index'))
            ->assertStatus(JsonResponse::HTTP_OK)
            ->assertJsonCount(5, 'data');
    }
}
