<?php

namespace App\Services\API\V1;

use App\DataTransferObjects\API\V1\Paginate\PaginateDTO;
use App\DataTransferObjects\API\V1\Review\StoreReviewDTO;
use App\DataTransferObjects\API\V1\Review\UpdateReviewDTO;
use App\Models\API\V1\Review;
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
}
