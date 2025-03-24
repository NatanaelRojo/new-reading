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

    protected function setUp(): void
    {
        parent::setUp();

        // ✅ Create a test user and authenticate with Sanctum
        $user = User::factory()->create();
        Sanctum::actingAs($user);
    }

    /**
     * A basic feature test example.
     */
    public function test_create_a_tag(): void
    {
        $newTag = Tag::factory()->make();
        $response = $this->postJson('/api/v1/tags', $newTag->toArray());

        $response
            ->assertStatus(JsonResponse::HTTP_CREATED);
    }

    public function test_update_a_tag(): void
    {
        $newTag = Tag::factory()->make();
        $otherTag = Tag::factory()->make();

        $newTag = $this->postJson('/api/v1/tags', $newTag->toArray());

        $response = $this->putJson('/api/v1/tags/' . $newTag['slug'], $otherTag->toArray());

        $response
            ->assertStatus(JsonResponse::HTTP_OK)
            ->assertJson($otherTag->toArray());
    }

    public function test_delete_a_tag(): void
    {
        $newTag = Tag::factory()->make();

        $newTag = $this->postJson('/api/v1/tags', $newTag->toArray());

        $response = $this->deleteJson('/api/v1/tags/' . $newTag['slug']);

        $response
            ->assertStatus(JsonResponse::HTTP_NO_CONTENT);
    }

    public function test_get_a_tag(): void
    {
        $newTag = Tag::factory()->make();

        $newTag = $this->postJson('/api/v1/tags', $newTag->toArray());

        $response = $this->getJson('/api/v1/tags/' . $newTag['slug']);

        $response
            ->assertStatus(JsonResponse::HTTP_OK)
            ->assertJson([
                'name' => $newTag['name'],
                'slug' => $newTag['slug'],
            ]);
    }

    public function test_get_tags(): void
    {
        $tags = Tag::factory()
            ->count(5)
            ->make();

        foreach ($tags as $tag) {
            $this->postJson('/api/v1/tags', $tag->toArray());
        }

        $response = $this->getJson('/api/v1/tags');

        $response
            ->assertStatus(JsonResponse::HTTP_OK)
            ->assertJsonCount(Tag::count());
    }
}
