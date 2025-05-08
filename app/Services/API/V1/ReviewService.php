<?php

namespace App\Services\API\V1;

use App\DataTransferObjects\API\V1\Paginate\PaginateDTO;
use App\DataTransferObjects\API\V1\Review\StoreReviewByBookDTO;
use App\DataTransferObjects\API\V1\Review\StoreReviewDTO;
use App\DataTransferObjects\API\V1\Review\UpdateReviewDTO;
use App\Exceptions\API\V1\Book\BookNotCompletedException;
use App\Exceptions\API\V1\Like\AlreadyDislikedException;
use App\Exceptions\API\V1\Like\AlreadyLikedException;
use App\Models\API\V1\Book;
use App\Models\API\V1\Review;
use App\Models\User;
use Illuminate\Pagination\LengthAwarePaginator;

/**
 * Service for ReviewService
 */
class ReviewService
{
    /**
     * Get all instances from the database
     */
    public function index(PaginateDTO $paginateDto): LengthAwarePaginator
    {
        return Review::with(['book', 'user'])
        ->paginate($paginateDto->perPage ?? 10);
    }
    /**
     * Store a new instance in the database
     */
    public function store(StoreReviewDTO $storeReviewDto): Review
    {
        return Review::query()
        ->create($storeReviewDto->toArray());
    }

    /**
     * Store a new instance in the database
     *
     * @param \App\DataTransferObjects\API\V1\Review\StoreReviewByBookDTO $storeReviewByBookDTO
     * @param \App\Services\API\V1\BookService $bookService
     * @throws \App\Exceptions\API\V1\Book\BookNotCompletedException
     * @return mixed|Review|\Illuminate\Http\JsonResponse
     */
    public function storeByBook(StoreReviewByBookDTO $storeReviewByBookDTO, BookService $bookService): Review
    {
        $book = Book::query()->firstWhere('id', $storeReviewByBookDTO->book_id);
        $user = User::query()->firstWhere('id', $storeReviewByBookDTO->user_id);

        if (!$bookService->isCompletedByUser($book, $user)) {
            throw new BookNotCompletedException();
        }

        return $book->reviews()
            ->create($storeReviewByBookDTO->toArray());
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
    public function update(UpdateReviewDTO $updateReviewDto, Review $review): void
    {
        $review->update($updateReviewDto->toArray());
    }

    /**
     * Delete an existing instance from the database
     */
    public function destroy(Review $review): void
    {
        $review->delete();
    }

    /**
     * Like a review.
     *
     * @param \App\Models\API\V1\Review $review
     * @param \App\Models\User $user
     * @throws \App\Exceptions\API\V1\Like\AlreadyLikedException
     * @return void
     */
    public function like(Review $review, User $user, UserService $userService): void
    {
        if ($review->likedBy($user)) {
            throw new AlreadyLikedException();
        }

        $userService->likeReview($user, $review);
    }

    /**
     * Dislike a review.
     *
     * @param \App\Models\API\V1\Review $review
     * @param \App\Models\User $user
     * @throws \App\Exceptions\API\V1\Like\AlreadyLikedException
     * @return void
     */
    public function dislike(Review $review, User $user, UserService $userService): void
    {
        if ($review->dislikeBy($user)) {
            throw new AlreadyDislikedException();
        }

        $userService->dislikeReview($user, $review);
    }
}
