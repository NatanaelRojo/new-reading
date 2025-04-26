<?php

namespace App\DataTransferObjects\API\V1\Review;

use App\DataTransferObjects\Base\BaseApiDTO;
use Spatie\DataTransferObject\DataTransferObject;

/**
 * Data Transfer Object for UpdateReview
 *
 * Auto-generated from App\Http\Requests\API\V1\Review\UpdateReviewRequest.
 */
class UpdateReviewDTO extends BaseApiDTO
{
    public function __construct(
        public readonly int $user_id,
        public readonly ?int $book_id = null,
        public readonly ?int $rating = null,
        public readonly ?string $comment = null,
    ) {
    }
}
