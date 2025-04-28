<?php

namespace App\Services\API\V1;

use App\DataTransferObjects\API\V1\Book\FilterBookDTO;
use App\DataTransferObjects\API\V1\Book\StoreBookDTO;
use App\DataTransferObjects\API\V1\Book\UpdateBookDTO;
use App\Models\API\V1\Book;
use Illuminate\Pagination\LengthAwarePaginator;

/**
 * Service for BookService
 */
class BookService
{
    /**
     * Get all instances from the database
     */
    public function index(FilterBookDTO $filterBookDto): LengthAwarePaginator
    {
        return Book::query()
        ->with([
            'authors',
            'genres',
        ])->filter($filterBookDto->toArray())
        ->paginate($filterBookDto->per_page ?? 10);
    }

    /**
     * Store a new instance in the database
     */
    public function store(StoreBookDTO $storeBookDto): Book
    {
        return Book::query()
        ->create($storeBookDto->toArray());
    }

    /**
     * Get a single instance from the database
     */
    public function show()
    {
        //
    }

    /**
     * Update an existing instance in the database
     */
    public function update(UpdateBookDTO $updateBookDto, Book $book): void
    {
        $book->update($updateBookDto->toArray());
    }

    /**
     * Delete an existing instance from the database
     */
    public function destroy(Book $book): void
    {
        $book->delete();
    }
}
