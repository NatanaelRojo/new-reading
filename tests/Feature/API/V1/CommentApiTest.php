<?php

namespace Tests\Feature\API\V1;

use App\Models\API\V1\Comment;
use App\Models\API\V1\Post;
use App\Models\API\V1\Review;
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
        Review::factory()
            ->count(20)
            ->create();

        // ✅ Create a test user and authenticate with Sanctum
        $this->user = User::factory()->create();
        Sanctum::actingAs($this->user);
    }

    /**
     * This PHP function tests for an empty comment list and asserts that the response is OK and the JSON
     * count is 0.
     */
    public function test_get_empty_comment_list(): void
    {
        $this->getJson(route('comments.index'))
        ->assertOk()
        ->assertJsonCount(0, 'data');
    }

    /**
     * The function tests creating a comment for a post by following the post's user and sending a JSON
     * request with a body to the appropriate route.
     */
    public function test_create_comment_for_a_post(): void
    {
        $post = Post::inRandomOrder()->first();
        $this->user->follow($post->user);

        $this->postJson(
            route('posts.comments.store', $post->slug),
            [
                'body' => fake()->sentence(),
            ]
        )->assertStatus(JsonResponse::HTTP_CREATED)
        ->assertJsonCount(1);
    }

    /**
     * The function `test_create_several_comments_for_a_post` creates multiple comments for a post and
     * asserts the successful creation of each comment.
     */
    public function test_create_several_comments_for_a_post(): void
    {
        $post = Post::inRandomOrder()->first();
        $this->user->follow($post->user);

        for ($i = 0; $i < 5; $i++) {
            $currentCommentBody = fake()->sentence();

            $this->postJson(
                route('posts.comments.store', $post->slug),
                [
                    'body' => $currentCommentBody,
                ]
            )->assertStatus(JsonResponse::HTTP_CREATED)
            ->assertJsonCount(1)
            ->assertJsonFragment([
                'body' => $currentCommentBody,
            ]);
        }
    }

    /**
     * The function `test_create_a_comment_for_a_review` tests creating a comment for a review in PHP
     * using Laravel testing.
     */
    public function test_create_a_comment_for_a_review(): void
    {
        $review = Review::inRandomOrder()->first();
        $this->user->follow($review->user);
        $reviewCommentBody = fake()->sentence();

        $this->postJson(
            route('reviews.comments.store', $review->id),
            [
                'body' => $reviewCommentBody,
            ]
        )->assertStatus(JsonResponse::HTTP_CREATED)
        ->assertJsonCount(1)
        ->assertJsonFragment([
            'body' => $reviewCommentBody,
        ]);

        $this->getJson(route('reviews.comments.index', $review->id))
        ->assertStatus(JsonResponse::HTTP_OK)
        ->assertJsonCount(1, 'data');
    }

    /**
     * The function `test_create_several_comments_for_a_review` creates multiple comments for a review
     * and asserts the successful creation of each comment.
     */
    public function test_create_several_comments_for_a_review(): void
    {
        $review = Review::inRandomOrder()->first();
        $this->user->follow($review->user);

        for ($i = 0; $i < 5; $i++) {
            $currentCommentBody = fake()->sentence();

            $this->postJson(
                route('reviews.comments.store', $review->id),
                [
                    'body' => $currentCommentBody,
                ]
            )->assertStatus(JsonResponse::HTTP_CREATED)
                ->assertJsonCount(1)
                ->assertJsonFragment([
                    'body' => $currentCommentBody,
                ]);
        }
    }

    /**
     * This function tests the functionality to show all comments for a specific post.
     */
    public function test_show_all_comments_for_a_post(): void
    {
        $post = Post::inRandomOrder()->first();
        $this->user->follow($post->user);

        for ($i = 0; $i < 5; $i++) {
            $this->postJson(
                route('posts.comments.store', $post->slug),
                [
                    'body' => fake()->sentence(),
                ]
            );
        }

        $this->getJson(route('posts.comments.index', $post->slug))
        ->assertStatus(JsonResponse::HTTP_OK)
        ->assertJsonCount(5, 'data');
    }

    /**
     * This function tests showing all comments for a review by creating 5 comments and then retrieving
     * them.
     */
    public function test_show_all_comments_for_a_review(): void
    {
        $review = Review::inRandomOrder()->first();
        $this->user->follow($review->user);

        for ($i = 0; $i < 5; $i++) {
            $this->postJson(
                route('reviews.comments.store', $review->id),
                [
                    'body' => fake()->sentence(),
                ]
            );
        }

        $this->getJson(route('reviews.comments.index', $review->id))
        ->assertStatus(JsonResponse::HTTP_OK)
        ->assertJsonCount(5, 'data');
    }

    /**
     * The function tests showing a comment for a user.
     */
    public function test_show_a_comment_for_a_user(): void
    {
        $comment = Comment::factory()->create();

        $this->getJson(
            route('comments.show', $comment->slug)
        )->assertStatus(JsonResponse::HTTP_OK)
        ->assertJsonCount(1);
    }

    /**
     * The function `test_update_a_comment` tests updating a comment's body and asserts the response
     * status, JSON count, and JSON fragment.
     */
    public function test_update_a_comment(): void
    {
        $comment = Comment::factory()->create();

        $newCommentBody = fake()->sentence();

        $this->putJson(
            route('comments.update', $comment->slug),
            [
                'body' => $newCommentBody,
            ]
        )->assertStatus(JsonResponse::HTTP_OK)
            ->assertJsonCount(1)
            ->assertJsonFragment([
                'body' => $newCommentBody,
            ]);
    }

    /**
     * The function `test_delete_a_comment` tests the deletion of a comment in a PHP application using
     * Laravel.
     */
    public function test_delete_a_comment(): void
    {
        $comment = Comment::factory()->create();

        $this->deleteJson(
            route('comments.destroy', $comment->slug)
        )->assertStatus(JsonResponse::HTTP_NO_CONTENT);

        $this->assertDatabaseMissing('comments', ['id' => $comment->id]);
    }
}
