<?php

namespace Tests\Feature\API\V1;

use App\Models\API\V1\Comment;
use App\Models\API\V1\Post;
use App\Models\API\V1\Tag;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\JsonResponse;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class CommentApiTest extends TestCase
{
    use RefreshDatabase;

    private User $user;

    protected function setUp(): void
    {
        parent::setUp();

        User::factory()
            ->count(10)
            ->create();
        Tag::factory()
            ->count(5)
            ->create();
        Post::factory()
            ->count(20)
            ->create();

        // ✅ Create a test user and authenticate with Sanctum
        $this->user = User::factory()->create();
        Sanctum::actingAs($this->user);
    }

    public function test_get_empty_comment_list(): void
    {
        $response = $this->getJson(route('comments.index'));

        $response->assertOk();
        $response->assertJsonCount(0);
    }

    public function test_create_comment_for_a_post(): void
    {
        $post = Post::inRandomOrder()->first();
        $this->user->follow($post->user);

        $response = $this->postJson(
            route('posts.comments.store', $post->slug),
            [
                'body' => fake()->sentence(),
            ]
        );

        $response->assertStatus(JsonResponse::HTTP_CREATED)
            ->assertJsonCount(1);

        $response = $this->getJson(route('posts.comments.index', $post->slug));
        $response->assertJsonCount(1);
    }

    public function test_create_several_comments_for_a_post(): void
    {
        $post = Post::inRandomOrder()->first();
        $this->user->follow($post->user);

        for ($i = 0; $i < 5; $i++) {
            $currentCommentBody = fake()->sentence();

            $response = $this->postJson(
                route('posts.comments.store', $post->slug),
                [
                    'body' => $currentCommentBody,
                ]
            );

            $response->assertStatus(JsonResponse::HTTP_CREATED)
                ->assertJsonCount(1)
                ->assertJsonFragment([
                    'body' => $currentCommentBody,
                ]);
        }
    }

    public function test_show_a_comment_for_a_user(): void
    {
        $comment = Comment::factory()->create();

        $response = $this->getJson(
            route('comments.show', $comment->slug)
        );

        $response->assertStatus(JsonResponse::HTTP_OK)
            ->assertJsonCount(1);
    }

    public function test_update_a_comment(): void
    {
        $comment = Comment::factory()->create();

        $newCommentBody = fake()->sentence();

        $response = $this->putJson(
            route('comments.update', $comment->slug),
            [
                'body' => $newCommentBody,
            ]
        );

        $response->assertStatus(JsonResponse::HTTP_OK)
            ->assertJsonCount(1)
            ->assertJsonFragment([
                'body' => $newCommentBody,
            ]);
    }

    public function test_delete_comment_for_a_post(): void
    {
        $comment = Comment::factory()->create();

        $response = $this->deleteJson(
            route('comments.destroy', $comment->slug)
        );

        $response->assertStatus(JsonResponse::HTTP_NO_CONTENT);

        $this->assertDatabaseMissing('comments', ['id' => $comment->id]);
    }
}
