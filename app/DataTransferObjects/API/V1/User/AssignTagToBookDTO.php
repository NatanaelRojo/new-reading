<?php

namespace App\DataTransferObjects\API\V1\User;

use App\DataTransferObjects\Base\BaseApiDTO;
use App\Models\API\V1\Book;
use App\Models\API\V1\Tag;
use App\Models\User;

/**
 * Data Transfer Object for AssignTagToBook
 *
 * Auto-generated from .
 */
class AssignTagToBookDTO extends BaseApiDTO
{
    public function __construct(
        public readonly int $book_id,
        public readonly int $user_id,
        public readonly int $tag_id,
    ) {
    }
}
