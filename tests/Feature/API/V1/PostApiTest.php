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

    /**
     * The setUp function sets up the environment for testing by creating instances of Tag, Book, and
     * User models, and authenticating a test user with Sanctum.
     */
    protected function setUp(): void
    {
        parent::setUp();

        Tag::factory()->count(5)->create();
        Book::factory()->count(5)->create();
        User::factory()->count(5)->create();

        // ✅ Create a test user and authenticate with Sanctum
        $this->user = User::factory()->create();
        Sanctum::actingAs($this->user);
    }

    /**
     * The function tests getting an empty list of posts and asserts that the response status is OK and
     * the JSON count is 0.
     */
    public function test_get_an_empty_list_of_posts(): void
    {
        $this->getJson(route('posts.index'))
        ->assertStatus(JsonResponse::HTTP_OK)
        ->assertJsonCount(0, 'data');
    }

    /**
     * The function `test_create_a_post` tests the creation of a post for a book by sending a POST
     * request with random data and then asserts the response status and JSON structure.
     */
    public function test_create_a_post(): void
    {
        $book = Book::inRandomOrder()->first();

        $this->postJson(
            route('books.posts.store', $book->slug),
            [
                'body' => fake()->sentence(),
                'progress' => fake()->numberBetween(0, 100),
            ]
        )->assertStatus(JsonResponse::HTTP_CREATED)
            ->assertJsonStructure([
                'body',
                'progress',
            ]);

        $response = $this->getJson(route('books.posts.index', $book->slug));
        $response->assertJsonCount(1, 'data');
    }

    /**
     * The function `test_create_a_post_for_a_user` tests the creation of a post for a user with
     * specific data and asserts the response status and JSON structure.
     */
    public function test_create_a_post_for_a_user(): void
    {
        $book = Book::inRandomOrder()->first();

        $this->postJson(
            route('users.posts.store', $this->user->id),
            [
                'book_id' => $book->id,
                'body' => fake()->sentence(),
                'progress' => fake()->numberBetween(0, 100),
            ]
        )->assertStatus(JsonResponse::HTTP_CREATED)
            ->assertJsonStructure([
                'body',
                'progress',
            ]);

        $this->getJson(route('books.posts.index', $book->slug))
            ->assertJsonCount(1, 'data');
    }

    /**
     * The function `test_get_all_posts_for_a_book` tests the retrieval of all posts for a specific
     * book by creating multiple posts and then checking if the correct number of posts is returned.
     */
    public function test_get_all_posts_for_a_book(): void
    {
        $book = Book::inRandomOrder()->first();
        $posts = Post::factory()
            ->count($this->postsAmount)
            ->for($book)
            ->create();

        $this->getJson(route('books.posts.index', $book->slug))
            ->assertStatus(JsonResponse::HTTP_OK)
            ->assertJsonCount($this->postsAmount, 'data');
    }

    /**
     * This function tests the retrieval of all posts for a specific user by creating multiple posts
     * and then checking if the correct number of posts are returned.
     */
    public function test_get_all_posts_for_a_user(): void
    {
        $book = Book::inRandomOrder()->first();
        Post::factory()
            ->count($this->postsAmount)
            ->for($this->user)
            ->for($book)
            ->create();

        $this->getJson(route('users.posts.index', $this->user->id))
        ->assertStatus(JsonResponse::HTTP_OK)
        ->assertJsonCount($this->postsAmount, 'data');
    }

    /**
     * The function `test_show_a_post` tests the API endpoint for displaying a post and asserts the
     * response status code and JSON structure.
     */
    public function test_show_a_post(): void
    {
        $post = Post::factory()->create();

        $this->getJson(route('posts.show', $post->slug))
        ->assertStatus(JsonResponse::HTTP_OK)
        ->assertJsonStructure([
            'body',
            'progress',
        ]);
    }

    /**
     * The function `test_update_a_post` tests updating a post in a PHP application using Laravel.
     */
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
        )->assertStatus(JsonResponse::HTTP_OK)
            ->assertJsonStructure([
                'body',
                'progress',
            ])->assertJsonFragment([
                'body' => $updatedPost->body,
                'progress' => $updatedPost->progress,
            ]);
    }

    /**
     * This function tests the deletion of a post in a PHP application.
     */
    public function test_delete_a_post(): void
    {
        $post = Post::factory()->create();

        $this->deleteJson(route('posts.destroy', $post->slug))
        ->assertStatus(JsonResponse::HTTP_NO_CONTENT);

        $this->assertDatabaseMissing('posts', [
            'id' => $post->id,
            'slug' => $post->slug,
        ]);
    }
}
