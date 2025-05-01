<?php

namespace App\Services\API\V1;

use App\DataTransferObjects\API\V1\Paginate\PaginateDTO;
use App\DataTransferObjects\API\V1\Review\StoreReviewDTO;
use App\DataTransferObjects\API\V1\Review\UpdateReviewDTO;
use App\Exceptions\API\V1\Like\AlreadyDislikedException;
use App\Exceptions\API\V1\Like\AlreadyLikedException;
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
    public function like(Review $review, User $user): void
    {
        if ($review->likedBy($user)) {
            throw new AlreadyLikedException();
        }

        $user->likeReview($review);
    }

    /**
     * Dislike a review.
     *
     * @param \App\Models\API\V1\Review $review
     * @param \App\Models\User $user
     * @throws \App\Exceptions\API\V1\Like\AlreadyLikedException
     * @return void
     */
    public function dislike(Review $review, User $user): void
    {
        if ($review->dislikeBy($user)) {
            throw new AlreadyDislikedException();
        }

        $user->dislikeReview($review);
    }
}
