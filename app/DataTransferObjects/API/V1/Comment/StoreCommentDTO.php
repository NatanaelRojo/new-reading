<?php

namespace App\DataTransferObjects\API\V1\Comment;

use App\DataTransferObjects\Base\BaseApiDTO;
use Spatie\DataTransferObject\DataTransferObject;

/**
 * Data Transfer Object for StoreComment
 *
 * Auto-generated from App\Http\Requests\API\V1\Comment\StoreCommentRequest.
 */
class StoreCommentDTO extends BaseApiDTO
{
    public function __construct(
        public readonly string $body,
        public readonly int $user_id,
        public readonly int $commentable_id,
        public readonly string $commentable_type,
    ) {
    }
}
