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

    /**
     * The setUp function initializes data for testing in a PHP unit test class.
     */
    protected function setUp(): void
    {
        parent::setUp();
        Tag::factory()->count(5)->create();
        foreach (config('tags.default_tags') as $tagName) {
            Tag::factory()->create(['name' => $tagName]);
        }
        Book::factory()->count(5)->create();

        $this->user = User::factory()->create();

        Sanctum::actingAs($this->user);
    }

    /**
     * The function `test_create_a_review` tests the creation of a review for a book in a PHP
     * application.
     */
    public function test_create_a_review(): void
    {
        $book = Book::inRandomOrder()->first();

        $this->postJson(route('reviews.store'), [
            'book_id' => $book->id,
            'rating' => fake()->numberBetween(1, 5),
            'comment' => fake()->text(),
        ])->assertStatus(JsonResponse::HTTP_CREATED)
        ->assertJsonStructure([
            'comment',
            'rating',
        ]);

        $this->getJson(route('reviews.index'))
            ->assertJsonCount(10, 'data');
    }

    /**
     * The function creates a review for a user for a specific book, including setting completion
     * status and verifying the review creation.
     */
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

        $this->postJson(
            route('books.reviews.store', $book->slug),
            [
            'rating' => fake()->numberBetween(1, 5),
            'comment' => fake()->text(),
        ]
        )->assertStatus(JsonResponse::HTTP_CREATED)
        ->assertJsonStructure([
            'comment',
            'rating',
        ]);

        $this->getJson(route('books.reviews.index', $book->slug))
            ->assertJsonCount(
                $book->reviews()->count() <= 10
                    ? $book->reviews()->count()
                    : 10,
                'data'
            );
    }

    /**
     * This function tests the retrieval of a list of reviews and asserts that the response status is
     * OK and the JSON count matches the total number of reviews.
     */
    public function test_get_a_list_of_reviews(): void
    {
        Review::factory()->count($this->reviewsAmount)->create();

        $this->getJson(route('reviews.index'))->assertStatus(JsonResponse::HTTP_OK)
        ->assertJsonCount(10, 'data');
    }

    /**
     * The function tests getting a list of reviews for a user.
     */
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
        )->assertStatus(JsonResponse::HTTP_OK)
        ->assertJsonCount(1, 'data');

        foreach ($response->json()['data'] as $review) {
            $this->assertArrayHasKey('comment', $review);
            $this->assertArrayHasKey('rating', $review);
        }
    }

    /**
     * The function `test_show_a_review` tests the endpoint for displaying a review and asserts the
     * response structure.
     */
    public function test_show_a_review(): void
    {
        $review = Review::factory()->create();

        $this->getJson(
            route('reviews.show', $review->id)
        )->assertStatus(JsonResponse::HTTP_OK)
        ->assertJsonStructure([
            'comment',
            'rating',
        ]);
    }

    /**
     * The function `test_update_a_review` tests updating a review in a Laravel application using
     * factory-generated data.
     */
    public function test_update_a_review(): void
    {
        $review = Review::factory()->create();
        $updatedReview = Review::factory()->make();

        $this->putJson(
            route('reviews.update', $review->id),
            $updatedReview->toArray()
        )->assertStatus(JsonResponse::HTTP_OK)
        ->assertJsonStructure([
            'comment',
            'rating',
        ]);
    }

    /**
     * The function `test_delete_a_review` tests the deletion of a review using Laravel's testing
     * framework.
     */
    public function test_delete_a_review(): void
    {
        $review = Review::factory()->create();

        $response = $this->deleteJson(
            route('reviews.destroy', $review->id)
        )->assertStatus(JsonResponse::HTTP_NO_CONTENT);

        $this->assertDatabaseMissing('reviews', ['id' => $review->id]);
    }

    /**
     * The function `test_like_a_review` tests the functionality of liking a review in a PHP
     * environment using Laravel testing.
     */
    public function test_like_a_review(): void
    {
        $review = Review::factory()->create();

        $this->postJson(
            route('reviews.like', $review->id)
        )->assertStatus(JsonResponse::HTTP_OK);
    }

    /**
     * The function `test_dislike_a_review` tests the functionality of disliking a review in a PHP
     * application using Laravel.
     */
    public function test_dislike_a_review(): void
    {
        $review = Review::factory()->create();

        $this->postJson(
            route('reviews.dislike', $review->id)
        )->assertStatus(JsonResponse::HTTP_OK);
    }
}
