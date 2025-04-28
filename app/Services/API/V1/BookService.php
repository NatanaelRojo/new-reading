<?php

namespace App\Services\API\V1;

use App\DataTransferObjects\API\V1\Book\FilterBookDTO;
use App\DataTransferObjects\API\V1\Book\StoreBookDTO;
use App\DataTransferObjects\API\V1\Book\UpdateBookDTO;
use App\DataTransferObjects\API\V1\Book\UpdateBookReadingProgressDTO;
use App\DataTransferObjects\API\V1\Book\UpdateBookTagDTO;
use App\Models\API\V1\Book;
use App\Models\API\V1\Tag;
use App\Models\User;
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

    /**
     * Update the progress of a user for a specific book.
     *
     * @param \App\DataTransferObjects\API\V1\Book\UpdateBookReadingProgressDTO $updateBookReadingProgressDto
     * @return void
     */
    public function updateUserProgress(UpdateBookReadingProgressDTO $updateBookReadingProgressDto): void
    {
        if (!is_null($updateBookReadingProgressDto->user) || !is_null($updateBookReadingProgressDto->pagesRead)) {
            $updateBookReadingProgressDto->pagesRead == $updateBookReadingProgressDto->book->pages_amount ? $this->completeBook($updateBookReadingProgressDto->book, $updateBookReadingProgressDto->user) : $updateBookReadingProgressDto->book->readingProgress = $updateBookReadingProgressDto->pagesRead;
        }
    }

    /**
     * Update the tag of a user for a specific book.
     *
     * @param \App\DataTransferObjects\API\V1\Book\UpdateBookTagDTO $updateBookTagDto
     * @return void
     */
    public function updateTag(UpdateBookTagDTO $updateBookTagDto): void
    {
        $updateBookTagDto->book->users()
            ->updateExistingPivot($updateBookTagDto->user->id, [
                'tag_id' => $updateBookTagDto->tagId,
            ]);
    }

    /**
     * Complete a book for a specific user.
     *
     * @param \App\Models\API\V1\Book $book
     * @param \App\Models\User $user
     * @return void
     */
    private function completeBook(Book $book, User $user): void
    {
        $tag = Tag::query()
            ->firstWhere('name', config('tags.default_tags')[2]);
        $book->users()->updateExistingPivot($user->id, [
            'tag_id' => $tag->id,
            'pages_read' => $book->pages_amount,
        ]);
    }
}
