<?php

namespace App\DataTransferObjects\API\V1\Comment;

use App\DataTransferObjects\Base\BaseApiDTO;
use App\Models\API\V1\Review;
use App\Models\User;
use Illuminate\Http\Request;
use Spatie\DataTransferObject\DataTransferObject;

/**
 * Data Transfer Object for StoreCommentByReview
 *
 * Auto-generated from App\Http\Requests\API\V1\Comment\StoreCommentRequest.
 */
class StoreCommentByReviewDTO extends BaseApiDTO
{
    public function __construct(
        public readonly string $body,
        public readonly int  $userId,
        public readonly int $reviewId,
        public readonly string $commentable_type,
    ) {
    }

    public static function fromRequest(Request $request): static
    {
        return new StoreCommentByReviewDTO(
            body: $request->body,
            userId: $request->user()->id,
            reviewId: $request->review->id,
            commentable_type: $request->review::class,
        );
    }

    public function toArray(bool $includeNulls = false): array
    {
        return [
            'body' => $this->body,
            'user_id' => $this->userId,
            'review_id' => $this->reviewId,
            'commentable_type' => $this->commentable_type,
        ];
    }
}
