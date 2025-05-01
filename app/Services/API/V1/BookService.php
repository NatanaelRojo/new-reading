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
use Illuminate\Validation\ValidationException;

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
        if (!is_null($updateBookReadingProgressDto->user)
            || !is_null($updateBookReadingProgressDto->pagesRead)) {
            $this->validateReadingProgress(
                $updateBookReadingProgressDto->book,
                $updateBookReadingProgressDto->user,
                $updateBookReadingProgressDto->pagesRead
            );

            $updateBookReadingProgressDto->pagesRead == $updateBookReadingProgressDto->book->pages_amount ?
                $this->completeBook($updateBookReadingProgressDto->book, $updateBookReadingProgressDto->user)
                : $this->setReadingProgress(
                    $updateBookReadingProgressDto->book,
                    $updateBookReadingProgressDto->user,
                    $updateBookReadingProgressDto->pagesRead
                );
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
     * Get the completion percentage of a book for a specific user.
     *
     * @param \App\Models\API\V1\Book $book
     * @param \App\Models\User $user
     * @return float|int|null
     */
    public function getUserCompletionPercentage(Book $book, User $user): ?int
    {
        $userBook = $book->users()->firstWhere('user_id', $user->id);
        $totalPages = $book->pages_amount;

        if (!$userBook || !$totalPages) {
            return null;
        }

        return $userBook->pivot->pages_read ? ($userBook->pivot->pages_read / $totalPages) * 100 : 0;
    }

    /**
     * Get the tag that a user has assigned to a specific book.
     *
     * @param \App\Models\API\V1\Book $book
     * @param \App\Models\User $user
     */
    public function getUserTag(Book $book, User $user): ?Tag
    {
        return $book->users()->firstWhere('user_id', $user->id)?->pivot?->tag;
    }

    /**
     * Get the completion percentage of a book for a specific user.
     *
     * @param \App\Models\API\V1\Book $book
     * @param \App\Models\User $user
     * @return bool
     */
    public function isCompletedByUser(Book $book, User $user): bool
    {
        $bookTag = $this->getUserTag($book, $user);

        return $bookTag && $bookTag->name === config('tags.default_tags')[2];
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

    private function setReadingProgress(Book $book, User $user, int $pagesRead): void
    {
        $book->users()->updateExistingPivot($user->id, [
            'pages_read' => $pagesRead,
        ]);
    }

    /**
     * Validate the reading progress of a book for a specific user.
     *
     * @param \App\Models\API\V1\Book $book
     * @param \App\Models\User $user
     * @param int $pagesRead
     * @return void
     */
    private function validateReadingProgress(Book $book, User $user, int $pagesRead): void
    {
        $currentProgress = $book->users()->where('user_id', $user->id)->value('pages_read');

        if ($pagesRead <= $currentProgress) {
            throw ValidationException::withMessages([
                'pages_read' => 'The new progress must be greater than the current progress.',
            ]);
        }
    }
}
