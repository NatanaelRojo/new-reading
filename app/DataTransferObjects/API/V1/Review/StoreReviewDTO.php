<?php

namespace App\DataTransferObjects\API\V1\Review;

use App\DataTransferObjects\Base\BaseApiDTO;
use Spatie\DataTransferObject\DataTransferObject;

/**
 * Data Transfer Object for StoreReview
 *
 * Auto-generated from App\Http\Requests\API\V1\Review\StoreReviewRequest.
 */
class StoreReviewDTO extends BaseApiDTO
{
    public function __construct(
        public readonly int $book_id,
        public readonly int $user_id,
        public readonly int $rating,
        public readonly string $comment,
    ) {
    }
}
