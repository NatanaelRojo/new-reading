<?php

namespace App\DataTransferObjects\API\V1\Book;

use App\DataTransferObjects\Base\BaseApiDTO;
use App\Models\API\V1\Book;
use App\Models\API\V1\Tag;
use App\Models\User;
use Spatie\DataTransferObject\DataTransferObject;

/**
 * Data Transfer Object for AssignTagToUser
 *
 * Auto-generated from .
 */
class AssignTagToUserDTO extends BaseApiDTO
{
    public function __construct(
        public readonly int $bookId,
        public readonly int $userId,
        public readonly int $tagId,
    ) {
    }
}
