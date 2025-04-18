<?php

namespace Tests\Feature\API\V1;

use App\Http\Controllers\API\V1\BookController;
use App\Http\Requests\API\V1\Book\UpdateBookRequest;
use App\Models\API\V1\Author;
use App\Models\API\V1\Book;
use App\Models\API\V1\Genre;
use App\Models\API\V1\Tag;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Testing\Fluent\AssertableJson;
use Illuminate\Testing\TestResponse;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class BookApiTest extends TestCase
{
    use RefreshDatabase;

    private User $user;

    /**
     * Setup the test environment.
     *
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        Tag::factory()->count(5)->create();

        // ✅ Create a test user and authenticate with Sanctum
        $this->user = User::factory()->create();
        Sanctum::actingAs($this->user);
    }

    /**
     * The function tests that an empty collection is returned when there are no books.
     */
    public function test_it_returns_an_empty_collection_when_there_are_no_books(): void
    {
        $this->getJson(route('books.index'))
            ->assertStatus(JsonResponse::HTTP_OK)
            ->assertJsonCount(0, 'data');
    }

    /**
     * This function tests the endpoint that returns a collection of books and checks if the response has
     * the expected structure and data.
     */
    public function test_it_returns_a_collection_of_books_with_ok_status(): void
    {
        Book::factory()->count(3)->create();

        $response = $this->getJson(route('books.index'));

        $response->assertStatus(JsonResponse::HTTP_OK)
            ->assertJsonCount(3, 'data');

        $data = $response->json('data');

        foreach ($data as $book) {
            $this->assertArrayHasKey('title', $book);
            $this->assertArrayHasKey('synopsis', $book);
            $this->assertArrayHasKey('isbn', $book);
            $this->assertArrayHasKey('pages_amount', $book);
            $this->assertArrayHasKey('chapters_amount', $book);
            $this->assertArrayHasKey('published_at', $book);
            $this->assertArrayHasKey('image_url', $book);
        }
    }

    /**
     * The function tests the ability to store a new book in a PHP application using Laravel.
     */
    public function test_it_can_store_a_new_book(): void
    {
        $newBook = Book::factory()->make();

        $this->postJson(route('books.store'), $newBook->toArray())
            ->assertStatus(JsonResponse::HTTP_CREATED)
            ->assertJson(
                fn (AssertableJson $json) =>
                $json->hasAll([
                    'title',
                    'synopsis',
                    'isbn',
                    'pages_amount',
                    'chapters_amount',
                    'published_at',
                    'rating',
                    'image_url',
                ])
            );
    }

    /**
     * The function `test_show_returns_a_book` tests that the API endpoint for showing a book returns
     * the expected book details in JSON format.
     */
    public function test_show_returns_a_book(): void
    {
        $book = Book::factory()->create();

        $this->getJson(route('books.show', $book->slug))
            ->assertStatus(JsonResponse::HTTP_OK)
            ->assertJson([
                'title' => $book->title,
                'synopsis' => $book->synopsis,
                'isbn' => $book->isbn,
                'pages_amount' => $book->pages_amount,
                'chapters_amount' => $book->chapters_amount,
                'published_at' => $book->published_at,
                'image_url' => $book->image_url,
            ]);
    }

    /**
     * The test function checks that the show endpoint returns a not found status for a nonexistent
     * book.
     */
    public function test_show_returns_not_found_for_nonexistent_book(): void
    {
        $book = Book::factory()->make();

        $this->getJson(route('books.show', 'invalid-slug'))
        ->assertStatus(JsonResponse::HTTP_NOT_FOUND);
    }

    /**
     * This function tests the update functionality for a book in a PHP application.
     */
    public function test_updates_a_book(): void
    {
        $book = Book::factory()->create();
        $updateBook = Book::factory()->make();

        $this->putJson(route('books.update', $book->slug), $updateBook->toArray())
        ->assertStatus(JsonResponse::HTTP_OK)
        ->assertJson([
            'title' => $updateBook->title,
            'synopsis' => $updateBook->synopsis,
            'isbn' => $updateBook->isbn,
            'pages_amount' => $updateBook->pages_amount,
            'chapters_amount' => $updateBook->chapters_amount,
            'published_at' => $updateBook->published_at,
        ]);
    }

    /**
     * The function tests updating a tag for a book in a PHP environment using Laravel.
     */
    public function test_update_a_tag_for_a_book(): void
    {
        $book = Book::factory()->create();
        $book->users()->attach($this->user);
        $tag = Tag::factory()->create();

        $this->putJson(
            route('books.tags.update', $book->slug),
            ['tag_id' => $tag->id]
        )->assertStatus(JsonResponse::HTTP_OK);


        $this->assertDatabaseHas('book_user', [
            'book_id' => $book->id,
            'tag_id' => $tag->id,
        ]);
    }

    /**
     * The function tests updating the reading progress for a book by incrementing the pages read and
     * asserting the database has the updated progress.
     */
    public function test_update_reading_progress_for_a_book(): void
    {
        $book = Book::factory()->create();
        $book->users()->attach($this->user);
        $newReadingProgress = $book->readingProgress + 1;

        $this->putJson(route('books.reading-progress.update', $book->slug), [
            'pages_read' => $newReadingProgress,
        ])->assertStatus(JsonResponse::HTTP_OK);
        ;

        $this->assertDatabaseHas('book_user', [
            'book_id' => $book->id,
            'pages_read' => $newReadingProgress,
        ]);
    }

    /**
     * The test_destroy_deletes_a_book function tests if deleting a book removes it from the database.
     */
    public function test_destroy_deletes_a_book(): void
    {
        $book = Book::factory()->create();

        $this->deleteJson(route('books.destroy', $book->slug))
            ->assertStatus(JsonResponse::HTTP_NO_CONTENT);

        $this->assertDatabaseMissing('books', ['id' => $book->id]);
    }

    /**
     * The function tests that all books are returned without any filters applied.
     */
    public function test_it_returns_all_books_without_filters(): void
    {
        Book::factory()
            ->count(10)
            ->create();

        $this->getJson(route('books.index'))
        ->assertStatus(JsonResponse::HTTP_OK)
        ->assertJsonCount(10, 'data');
    }

    /**
     * The function tests the endpoint that returns filtered books by title.
     */
    public function test_it_returns_filtered_books_by_title(): void
    {
        $book = Book::factory()->create(['title' => 'Test Book']);

        $this->getJson(route('books.index') . "?title={$book->title}")
                ->assertStatus(JsonResponse::HTTP_OK)
                ->assertJsonFragment([
                    'title' => $book->title,
                ]);
    }

    /**
     * The function tests the functionality of returning filtered books by author in a PHP application.
     */
    public function test_it_returns_filtered_books_by_author(): void
    {
        $books = Book::factory()
            ->count(10)
            ->create();
        $author = Author::factory()->create();

        $book = $books->first();
        $book->authors()->attach($author);

        $this->getJson(route('books.index') . "?author_name={$author->name}")
            ->assertStatus(JsonResponse::HTTP_OK)
            ->assertJsonFragment([
                'title' => $book->title,
            ]);
    }

    /**
     * The function tests the functionality of returning filtered books by genre in a PHP application.
     */
    public function test_it_returns_filtered_books_by_genre(): void
    {
        $books = Book::factory()
            ->count(10)
            ->create();
        $genre = Genre::factory()->create();

        $book = $books->first();
        $book->genres()->attach($genre);

        $this->getJson(route('books.index') . "?genre_name={$genre->name}")
            ->assertStatus(JsonResponse::HTTP_OK)
            ->assertJsonFragment([
                'title' => $book->title,
            ]);
    }

    /**
     * This function tests the functionality of returning filtered books by title and author.
     */
    public function test_it_returns_filtered_books_by_title_and_author(): void
    {
        $books = Book::factory()
            ->count(10)
            ->create();
        $author = Author::factory()->create();

        $book = $books->first();
        $book->authors()->attach($author);

        $this->getJson(route('books.index') . "?title={$book->title}&author_name={$author->name}")
        ->assertStatus(JsonResponse::HTTP_OK)
        ->assertJsonCount(1, 'data')
        ->assertJsonFragment([
            'title' => $book->title,
        ]);
    }

    /**
     * This function tests the functionality of returning filtered books by title and genre.
     */
    public function test_it_returns_filtered_books_by_title_and_genre(): void
    {
        $books = Book::factory()
            ->count(10)
            ->create();
        $genre = Genre::factory()->create();

        $book = $books->first();
        $book->genres()->attach($genre);

        $this->getJson(route('books.index') . "?title={$book->title}&genre_name={$genre->name}")
        ->assertStatus(JsonResponse::HTTP_OK)
        ->assertJsonCount(1, 'data')
        ->assertJsonFragment([
            'title' => $book->title,
        ]);
    }

    /**
     * The function tests the functionality of returning filtered books by title, author, and genre.
     */
    public function test_it_returns_filtered_books_by_title_and_author_and_genre(): void
    {
        $books = Book::factory()
            ->count(10)
            ->create();
        $author = Author::factory()->create();
        $genre = Genre::factory()->create();

        $book = $books->first();
        $book->authors()->attach($author);
        $book->genres()->attach($genre);

        $this->getJson(
            route('books.index') . '?' .
                "title={$book->title}" .
                "&author_name={$author->name}" .
                "&genre_name={$genre->name}"
        )->assertStatus(JsonResponse::HTTP_OK)
            ->assertJsonCount(1, 'data')
            ->assertJsonFragment([
                'title' => $book->title,
            ]);
    }

    /**
     * The function tests the functionality of returning filtered books by author and genre in a PHP
     * application.
     */
    public function test_it_returns_filtered_books_by_author_and_genre(): void
    {
        $books = Book::factory()
        ->count(20)
        ->create();
        $author = Author::factory()->create();
        $genre = Genre::factory()->create();

        $book = $books->first();
        $book->authors()->attach($author);
        $book->genres()->attach($genre);

        $this->getJson(route('books.index') . "?author_name={$author->name}&genre_name={$genre->name}")
        ->assertStatus(JsonResponse::HTTP_OK)
        ->assertJsonCount(1, 'data')
        ->assertJsonFragment([
            'title' => $book->title,
        ]);
    }

    /**
     * The function tests the functionality of returning filtered books by tag in a PHP environment.
     */
    public function test_it_returns_filtered_books_by_tag(): void
    {
        $book = Book::factory()
            ->create();
        $tag = Tag::factory()->create();
        $bookRandomUser = $book->users()->inRandomOrder()->first();

        $pivot = $book->users()->firstWhere('user_id', $bookRandomUser->id)->pivot;
        $pivot->tag_id = $tag->id;
        $pivot->save();

        $this->getJson(route('books.index') . "?tag_name={$tag->name}")
        ->assertStatus(JsonResponse::HTTP_OK)
        ->assertJsonCount(1, 'data')
        ->assertJsonFragment([
            'title' => $book->title,
        ]);
    }

    /**
     * The function tests the functionality of returning filtered books by year.
     */
    public function test_it_returns_filtered_books_by_year(): void
    {
        $month = Carbon::now()->month;
        $day = Carbon::now()->day;
        $year = Carbon::now()->year;

        Book::factory()
            ->create(['published_at' => "{$year}-{$month}-{$day}"]);

        $this->getJson(route('books.index') . "?year={$year}")
        ->assertStatus(JsonResponse::HTTP_OK)
        ->assertJsonCount(1, 'data');
    }

    /**
     * The function tests the completion of a book for a user by creating a book with 200 pages, a
     * user, and a tag, then attaching the user to the book and marking it as completed with all
     * relevant details stored in the database.
     */
    public function test_it_can_complete_a_book_for_a_user(): void
    {
        $book = Book::factory()->create(['pages_amount' => 200]);
        $user = User::factory()->create();
        $tag = Tag::factory()->create(['name' => 'Completed']);

        $book->users()->attach($user->id);
        $book->completeBook($user);

        $this->assertDatabaseHas('book_user', [
            'book_id' => $book->id,
            'user_id' => $user->id,
            'tag_id' => $tag->id,
            'pages_read' => 200,
        ]);
    }
}
