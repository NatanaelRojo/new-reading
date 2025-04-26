<?php

namespace App\DataTransferObjects\API\V1\Post;

use App\DataTransferObjects\Base\BaseApiDTO;
use Spatie\DataTransferObject\DataTransferObject;

/**
 * Data Transfer Object for StorePost
 *
 * Auto-generated from App\Http\Requests\API\V1\Post\StorePostRequest.
 */
class StorePostDTO extends BaseApiDTO
{
    public function __construct(
        public readonly string $body,
        public readonly int $progress,
        public readonly int $book_id,
        public readonly int $user_id,
    ) {
    }
}
