<?php

namespace Tests\Feature\API\V1;

use App\Http\Controllers\API\V1\Controllers\BookController;
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
use Tests\TestCase;

class BookApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_returns_an_empty_collection_when_there_are_no_books()
    {
        // Arrange
        $controller = new BookController();

        // Act
        $response = $controller->index();

        // Assert
        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(JsonResponse::HTTP_OK, $response->getStatusCode());

        $responseData = json_decode($response->getContent(), true);
        $this->assertEmpty($responseData);
    }

    public function test_it_returns_a_collection_of_books_with_ok_status()
    {
        // Arrange
        Book::factory()->count(3)->create();
        $controller = new BookController();

        // Act
        $response = $controller->index();

        // Assert
        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(JsonResponse::HTTP_OK, $response->getStatusCode());

        $responseData = json_decode($response->getContent(), true);
        $this->assertCount(3, $responseData);

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

    public function test_it_can_store_a_new_book()
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
}
