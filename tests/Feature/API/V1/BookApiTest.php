<?php

namespace Tests\Feature\API\V1;

use App\Http\Controllers\API\V1\Controllers\BookController;
use App\Http\Requests\API\V1\Book\UpdateBookRequest;
use App\Http\Resources\API\V1\Book\BookResource;
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
use Illuminate\Testing\TestResponse;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class BookApiTest extends TestCase
{
    use RefreshDatabase;

    private User $user;

    protected function setUp(): void
    {
        parent::setUp();

        Tag::factory()->count(5)->create();

        // ✅ Create a test user and authenticate with Sanctum
        $this->user = User::factory()->create();
        Sanctum::actingAs($this->user);
    }

    public function test_it_returns_an_empty_collection_when_there_are_no_books(): void
    {
        // Act
        $response = $this->getJson(route('books.index'));

        // Assert
        $response->assertStatus(JsonResponse::HTTP_OK)
            ->assertJsonCount(0);
    }

    public function test_it_returns_a_collection_of_books_with_ok_status(): void
    {
        // Arrange
        Book::factory()->count(3)->create();

        // Act
        $response = $this->getJson(route('books.index'));

        // Assert
        $response->assertStatus(JsonResponse::HTTP_OK)
            ->assertJsonCount(Book::count());

        $responseData = $response->json();

        // Check if each book is a valid BookResource
        foreach ($responseData as $bookData) {
            $this->assertArrayHasKey('title', $bookData);
            $this->assertArrayHasKey('synopsis', $bookData);
            $this->assertArrayHasKey('isbn', $bookData);
            $this->assertArrayHasKey('pages_amount', $bookData);
            $this->assertArrayHasKey('chapters_amount', $bookData);
            $this->assertArrayHasKey('published_at', $bookData);
            $this->assertArrayHasKey('image_url', $bookData);
        }
    }

    public function test_it_can_store_a_new_book(): void
    {
        // Arrange
        $newBook = Book::factory()->make();

        // Act
        $response = $this->postJson('/api/v1/books', $newBook->toArray());

        // Assert
        $response->assertStatus(JsonResponse::HTTP_CREATED);
        $responseData = $response->json();
        $this->assertArrayHasKey('title', $responseData);
        $this->assertArrayHasKey('synopsis', $responseData);
        $this->assertArrayHasKey('isbn', $responseData);
        $this->assertArrayHasKey('pages_amount', $responseData);
        $this->assertArrayHasKey('chapters_amount', $responseData);
        $this->assertArrayHasKey('published_at', $responseData);
        $this->assertArrayHasKey('image_url', $responseData);
        $this->assertDatabaseHas('books', ['title' => $newBook->title]);
    }

    public function test_show_returns_a_book(): void
    {
        // Arrange
        $book = Book::factory()->create();
        $controller = new BookController();

        // Act
        $response = $controller->show($book);

        // Assert
        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(JsonResponse::HTTP_OK, $response->getStatusCode());

        $responseData = json_decode($response->getContent(), true);
        $this->assertArrayHasKey('title', $responseData);
        $this->assertArrayHasKey('synopsis', $responseData);
        $this->assertArrayHasKey('isbn', $responseData);
        $this->assertArrayHasKey('pages_amount', $responseData);
        $this->assertArrayHasKey('chapters_amount', $responseData);
        $this->assertArrayHasKey('published_at', $responseData);
        $this->assertArrayHasKey('image_url', $responseData);

        $this->assertEquals($book->title, $responseData['title']);
        $this->assertEquals($book->synopsis, $responseData['synopsis']);
        $this->assertEquals($book->isbn, $responseData['isbn']);
        $this->assertEquals($book->pages_amount, $responseData['pages_amount']);
        $this->assertEquals($book->chapters_amount, $responseData['chapters_amount']);
        $this->assertEquals($book->published_at, $responseData['published_at']);
        $this->assertEquals($book->image_url, $responseData['image_url']);
    }

    public function test_show_returns_not_found_for_nonexistent_book(): void
    {
        // Arrange
        $book = Book::factory()->make();
        $controller = new BookController();

        // Act
        $response = $this->getJson('/api/v1/books/not-existing-slug');
        // $this->expectException(\Illuminate\Database\Eloquent\ModelNotFoundException::class);
        // Assert
        $response->assertStatus(JsonResponse::HTTP_NOT_FOUND);
    }

    public function test_updates_a_book(): void
    {
        // Arrange
        $book = Book::factory()->create();
        $updatedData = [
            'title' => 'Updated Book Title',
            'published_at' => '2023-01-01',
            'synopsis' => 'Updated book description',
        ];

        // Act
        $response = $this->putJson(route('books.update', $book), $updatedData);

        // Assert
        $response->assertStatus(JsonResponse::HTTP_OK);
        $response->assertJsonFragment($updatedData);
        $this->assertDatabaseHas('books', $updatedData);
    }

    public function test_update_a_tag_for_a_book(): void
    {
        $book = Book::factory()->create();
        $tag = Tag::factory()->create();

        $response = $this->putJson(route('books.tags.update', $book->slug), ['tag_id' => $tag->id]);

        $response->assertStatus(JsonResponse::HTTP_OK);

        $this->assertDatabaseHas('book_user', [
            'book_id' => $book->id,
            'tag_id' => $tag->id,
        ]);
    }

    public function test_update_reading_progress_for_a_book(): void
    {
        $book = Book::factory()->create();
        $book->users()->attach($this->user);
        $newReadingProgress = $book->readingProgress + 1;

        $response = $this->putJson(route('books.reading-progress.update', $book->slug), [
            'pages_read' => $newReadingProgress,
        ]);

        $response->assertStatus(JsonResponse::HTTP_OK);

        $this->assertDatabaseHas('book_user', [
            'book_id' => $book->id,
            'pages_read' => $newReadingProgress,
        ]);
    }

    public function test_destroy_deletes_a_book(): void
    {
        // Arrange
        $book = Book::factory()->create();
        $controller = new BookController();

        // Act
        $response = $controller->destroy($book);

        // Assert
        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(JsonResponse::HTTP_NO_CONTENT, $response->getStatusCode());
        $this->assertDatabaseMissing('books', ['id' => $book->id]);
    }

    public function test_it_returns_all_books_without_filters(): void
    {
        $allBooks = $this->getJson(route('books.index'));
        $response = $this->getJson(route('books.index'));

        $response->assertStatus(JsonResponse::HTTP_OK)
            ->assertJsonCount(count($allBooks->json()));
    }

    public function test_it_returns_filtered_books_by_title(): void
    {
        $book = Book::factory()->create(['title' => 'Test Book']);

        $response = $this->getJson(route('books.index') . "?title={$book->title}");

        $response->assertStatus(JsonResponse::HTTP_OK)
            ->assertJsonFragment([
                'title' => $book->title,
            ]);
    }

    public function test_it_returns_filtered_books_by_author(): void
    {
        $books = Book::factory()
            ->count(10)
            ->create();
        $author = Author::factory()->create();

        $book = $books->first();
        $book->authors()->attach($author);

        $response = $this->getJson(route('books.index') . "?author_name={$author->name}");

        $response->assertStatus(JsonResponse::HTTP_OK)
            ->assertJsonFragment([
                'title' => $book->title,
            ]);
    }

    public function test_it_returns_filtered_books_by_genre(): void
    {
        $books = Book::factory()
            ->count(10)
            ->create();
        $genre = Genre::factory()->create();

        $book = $books->first();
        $book->genres()->attach($genre);

        $response = $this->getJson(route('books.index') . "?genre_name={$genre->name}");

        $response->assertStatus(JsonResponse::HTTP_OK)
            ->assertJsonFragment([
                'title' => $book->title,
            ]);
    }

    public function test_it_returns_filtered_books_by_title_and_author(): void
    {
        $books = Book::factory()
            ->count(10)
            ->create();
        $author = Author::factory()->create();

        $book = $books->first();
        $book->authors()->attach($author);

        $response = $this->getJson(route('books.index') . "?title={$book->title}&author_name={$author->name}");

        $response->assertStatus(JsonResponse::HTTP_OK)
            ->assertJsonCount(1)
            ->assertJsonFragment([
                'title' => $book->title,
            ]);
    }

    public function test_it_returns_filtered_books_by_title_and_genre(): void
    {
        $books = Book::factory()
            ->count(10)
            ->create();
        $genre = Genre::factory()->create();

        $book = $books->first();
        $book->genres()->attach($genre);

        $response = $this->getJson(route('books.index') . "?title={$book->title}&genre_name={$genre->name}");

        $response->assertStatus(JsonResponse::HTTP_OK)
            ->assertJsonCount(1)
            ->assertJsonFragment([
                'title' => $book->title,
            ]);
    }

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

        $response = $this->getJson(route('books.index') . "?title={$book->title}&author_name={$author->name}&genre_name={$genre->name}");

        $response->assertStatus(JsonResponse::HTTP_OK)
            ->assertJsonCount(1)
            ->assertJsonFragment([
                'title' => $book->title,
            ]);
    }

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

        $response = $this->getJson(route('books.index') . "?author_name={$author->name}&genre_name={$genre->name}");

        $response->assertStatus(JsonResponse::HTTP_OK)
            ->assertJsonCount(1)
            ->assertJsonFragment([
                'title' => $book->title,
            ]);
    }

    public function test_it_returns_filtered_books_by_tag(): void
    {
        $book = Book::factory()
            ->create();
        $tag = Tag::factory()->create();
        $bookRandomUser = $book->users()->inRandomOrder()->first();

        $pivot = $book->users()->firstWhere('user_id', $bookRandomUser->id)->pivot;
        $pivot->tag_id = $tag->id;
        $pivot->save();

        $response = $this->getJson(route('books.index') . "?tag_name={$tag->name}");

        $response->assertStatus(JsonResponse::HTTP_OK)
            ->assertJsonCount(1)
            ->assertJsonFragment([
                'title' => $book->title,
            ]);
    }

    public function test_it_returns_filtered_books_by_year(): void
    {
        $month = Carbon::now()->month;
        $day = Carbon::now()->day;
        $year = Carbon::now()->year;

        Book::factory()
            ->create(['published_at' => "{$year}-{$month}-{$day}"]);

        $response = $this->getJson(route('books.index') . "?year={$year}");

        $response->assertStatus(JsonResponse::HTTP_OK)
            ->assertJsonCount(1);
    }

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
