<?php

namespace App\DataTransferObjects\API\V1\Post;

use App\DataTransferObjects\Base\BaseApiDTO;
use Spatie\DataTransferObject\DataTransferObject;

/**
 * Data Transfer Object for UpdatePost
 *
 * Auto-generated from App\Http\Requests\API\V1\Post\UpdatePostRequest.
 */
class UpdatePostDTO extends BaseApiDTO
{
    public function __construct(
        public readonly int $book_id,
        public readonly int $user_id,
        public readonly ?int $progress = null,
        public readonly ?string $body = null,
    ) {
    }
}
