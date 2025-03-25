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

    public function test_updates_a_book_with_a_user_and_tag(): void
    {
        // Arrange
        $users = User::factory()
            ->count(3)
            ->create();
        $tags = Tag::factory()
            ->count(3)
            ->create();
        $book = Book::factory()->create();

        $updatedData = [
            'title' => 'Updated Book Title',
            'published_at' => '2023-01-01',
            'synopsis' => 'Updated book description',
            'pages_read' => 10,
            'tag_id' => $tags[0]->id,
            'user_id' => $book->users[0]->id,
        ];

        // Act
        $response = $this->putJson(route('books.update', $book), $updatedData);

        // Assert
        $response->assertStatus(JsonResponse::HTTP_OK);
        $response->assertJsonFragment([
            'title' => $updatedData['title'],
            'synopsis' => $updatedData['synopsis'],
            'published_at' => $updatedData['published_at'],
        ]);
        $this->assertDatabaseHas('books', ['title' => 'Updated Book Title', 'synopsis' => 'Updated book description']);
        $this->assertDatabaseHas('book_user', [
            'book_id' => $book->id,
            'user_id' => $this->user->id,
            'tag_id' => $updatedData['tag_id'],
            'pages_read' => $updatedData['pages_read'],
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
}
