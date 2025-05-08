<?php

namespace App\DataTransferObjects\API\V1\Review;

use App\DataTransferObjects\Base\BaseApiDTO;
use App\Models\API\V1\Book;
use App\Models\User;
use Illuminate\Http\Request;

/**
 * Data Transfer Object for StoreReviewByBook
 *
 * Auto-generated from App\Http\Requests\API\V1\Review\StoreReviewRequest.
 */
class StoreReviewByBookDTO extends BaseApiDTO
{
    public function __construct(
        public readonly int $book_id,
        public readonly int $user_id,
        public readonly int $rating,
        public readonly string $comment,
    ) {
    }
}
