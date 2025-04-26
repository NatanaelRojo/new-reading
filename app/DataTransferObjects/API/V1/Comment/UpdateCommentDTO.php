<?php

namespace App\DataTransferObjects\API\V1\Comment;

use App\DataTransferObjects\Base\BaseApiDTO;
use Spatie\DataTransferObject\DataTransferObject;

/**
 * Data Transfer Object for UpdateComment
 *
 * Auto-generated from App\Http\Requests\API\V1\Comment\UpdateCommentRequest.
 */
class UpdateCommentDTO extends BaseApiDTO
{
    public function __construct(
        public readonly string $body,
    ) {
    }
}
