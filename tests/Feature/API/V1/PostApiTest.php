<?php

namespace Tests\Feature\API\V1;

use App\Models\API\V1\Book;
use App\Models\API\V1\Post;
use App\Models\API\V1\Tag;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\JsonResponse;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class PostApiTest extends TestCase
{
    use RefreshDatabase;

    private User $user;
    private int $postsAmount = 5;

    protected function setUp(): void
    {
        parent::setUp();

        Book::factory()->count(5)->create();
        Tag::factory()->count(5)->create();
        User::factory()->count(5)->create();

        // ✅ Create a test user and authenticate with Sanctum
        $this->user = User::factory()->create();
        Sanctum::actingAs($this->user);
    }

    public function test_get_an_empty_list_of_posts(): void
    {
        $response = $this->getJson(route('posts.index'));

        $response->assertStatus(JsonResponse::HTTP_OK)
            ->assertJsonCount(0);
    }

    public function test_create_a_post(): void
    {
        $book = Book::inRandomOrder()->first();

        $response = $this->postJson(
            route('books.posts.store', $book->slug),
            [
                'body' => fake()->sentence(),
                'progress' => fake()->numberBetween(0, 100),
            ]
        );

        $response->assertStatus(JsonResponse::HTTP_CREATED)
            ->assertJsonStructure([
                'body',
                'progress',
            ]);

        $response = $this->getJson(route('books.posts.index', $book->slug));
        $response->assertJsonCount(1);
    }

    public function test_create_a_post_for_a_user(): void
    {
        $book = Book::inRandomOrder()->first();

        $response = $this->postJson(
            route('users.posts.store', $this->user->id),
            [
                'book_id' => $book->id,
                'body' => fake()->sentence(),
                'progress' => fake()->numberBetween(0, 100),
            ]
        );

        $response->assertStatus(JsonResponse::HTTP_CREATED)
            ->assertJsonStructure([
                'body',
                'progress',
            ]);

        $response = $this->getJson(route('books.posts.index', $book->slug));
        $response->assertJsonCount(1);
    }

    public function test_get_all_posts_for_a_book(): void
    {
        $book = Book::inRandomOrder()->first();

        for ($i = 0; $i < $this->postsAmount; $i++) {
            $this->postJson(
                route('books.posts.store', $book->slug),
                [
                    'body' => fake()->sentence(),
                    'progress' => fake()->numberBetween(0, 100),
                ]
            );
        }

        $response = $this->getJson(route('books.posts.index', $book->slug));

        $response->assertStatus(JsonResponse::HTTP_OK)
            ->assertJsonCount($this->postsAmount);
    }

    public function test_get_all_posts_for_a_user(): void
    {
        $book = Book::inRandomOrder()->first();

        for ($i = 0; $i < $this->postsAmount; $i++) {
            $this->postJson(
                route('users.posts.store', $this->user->id),
                [
                    'book_id' => $book->id,
                    'body' => fake()->sentence(),
                    'progress' => fake()->numberBetween(0, 100),
                ]
            );
        }

        $response = $this->getJson(route('users.posts.index', $this->user->id));

        $response->assertStatus(JsonResponse::HTTP_OK)
            ->assertJsonCount($this->postsAmount);
    }

    public function test_show_a_post(): void
    {
        $post = Post::factory()->create();

        $response = $this->getJson(route('posts.show', $post->slug));

        $response->assertStatus(JsonResponse::HTTP_OK)
        ->assertJsonStructure([
            'body',
            'progress',
        ]);
    }

    public function test_update_a_post(): void
    {
        $post = Post::factory()->create();
        $updatedPost = Post::factory()->make();

        $response = $this->patchJson(
            route('posts.update', $post->slug),
            [
                'body' => $updatedPost->body,
                'progress' => $updatedPost->progress,
            ]
        );

        $response->assertStatus(JsonResponse::HTTP_OK)
        ->assertJsonStructure([
            'body',
            'progress',
        ])->assertJsonFragment([
            'body' => $updatedPost->body,
            'progress' => $updatedPost->progress,
        ]);
    }

    public function test_delete_a_post(): void
    {
        $post = Post::factory()->create();

        $response = $this->deleteJson(route('posts.destroy', $post->slug));

        $response->assertStatus(JsonResponse::HTTP_NO_CONTENT);

        $this->assertDatabaseMissing('posts', [
            'id' => $post->id,
            'slug' => $post->slug,
        ]);
    }
}
