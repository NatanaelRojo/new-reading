<?php

namespace Tests\Feature\API\V1;

use App\Models\API\V1\Book;
use App\Models\API\V1\Review;
use App\Models\API\V1\Tag;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\JsonResponse;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class ReviewApiTest extends TestCase
{
    use RefreshDatabase;

    private User $user;
    private int $reviewsAmount = 5;

    protected function setUp(): void
    {
        parent::setUp();
        Book::factory()->count(5)->create();
        Tag::factory()->count(5)->create();
        foreach (config('tags.default_tags') as $tagName) {
            Tag::factory()->create(['name' => $tagName]);
        }

        $this->user = User::factory()->create();

        Sanctum::actingAs($this->user);
    }

    public function test_get_a_empty_list_of_reviews(): void
    {
        $this->getJson(route('reviews.index'))
            ->assertOk()
            ->assertJsonCount(0);
    }

    public function test_create_a_review(): void
    {
        $book = Book::inRandomOrder()->first();

        $response = $this->postJson(route('reviews.store'), [
            'book_id' => $book->id,
            'rating' => fake()->numberBetween(1, 5),
            'comment' => fake()->text(),
        ]);

        $response->assertStatus(JsonResponse::HTTP_CREATED)
            ->assertJsonStructure([
                'comment',
                'rating',
            ]);

        $response = $this->getJson(route('reviews.index'));
        $response->assertJsonCount(1);
    }

    public function test_create_a_review_for_a_user(): void
    {
        $book = Book::inRandomOrder()->first();
        $tag = Tag::factory()->create(['name' => 'Completed']);
        $book->users()->attach($this->user);
        $pivot = $book->users()->firstWhere('user_id', $this->user->id)->pivot;
        $pivot->tag_id = $tag->id;
        $pivot->pages_read = $book->pages_amount;
        $pivot->save();
        $book->completeBook($this->user);

        $response = $this->postJson(
            route('books.reviews.store', $book->slug),
            [
            'rating' => fake()->numberBetween(1, 5),
            'comment' => fake()->text(),
        ]
        );

        $response->assertStatus(JsonResponse::HTTP_CREATED)
            ->assertJsonStructure([
                'comment',
                'rating',
            ]);

        $response = $this->getJson(route('books.reviews.index', $book->slug));
        $response->assertJsonCount(1);
    }

    public function test_get_a_list_of_reviews(): void
    {
        Review::factory()->count($this->reviewsAmount)->create();

        $this->getJson(route('reviews.index'))->assertStatus(JsonResponse::HTTP_OK)
        ->assertJsonCount(5);
    }

    public function test_get_a_list_of_reviews_for_a_user(): void
    {
        $book = Book::factory()->create();
        $review = Review::factory()->create();
        $book->completeBook($this->user);
        $review->update([
            'user_id' => $this->user->id,
            'book_id' => $book->id,
        ]);

        $response = $this->getJson(
            route('users.reviews.index', $this->user->id)
        );

        $response->assertStatus(JsonResponse::HTTP_OK)
            ->assertJsonCount(1);
    }

    public function test_show_a_review(): void
    {
        $review = Review::factory()->create();

        $response = $this->getJson(
            route('reviews.show', $review->id)
        );

        $response->assertStatus(JsonResponse::HTTP_OK)
            ->assertJsonStructure([
                'comment',
                'rating',
            ]);
    }

    public function test_update_a_review(): void
    {
        $review = Review::factory()->create();
        $updatedReview = Review::factory()->make();

        $response = $this->putJson(
            route('reviews.update', $review->id),
            $updatedReview->toArray()
        );

        $response->assertStatus(JsonResponse::HTTP_OK)
            ->assertJsonStructure([
                'comment',
                'rating',
            ]);
    }

    public function test_delete_a_review(): void
    {
        $review = Review::factory()->create();

        $response = $this->deleteJson(
            route('reviews.destroy', $review->id)
        );

        $response->assertStatus(JsonResponse::HTTP_NO_CONTENT);

        $this->assertDatabaseMissing('reviews', ['id' => $review->id]);
    }
}
