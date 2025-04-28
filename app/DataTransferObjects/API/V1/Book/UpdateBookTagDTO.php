<?php

namespace App\DataTransferObjects\API\V1\Book;

use App\DataTransferObjects\Base\BaseApiDTO;
use App\Models\API\V1\Book;
use App\Models\User;
use Spatie\DataTransferObject\DataTransferObject;

/**
 * Data Transfer Object for UpdateBookTag
 *
 * Auto-generated from App\Http\Requests\API\V1\Book\UpdateBookTagRequest.
 */
class UpdateBookTagDTO extends BaseApiDTO
{
    public function __construct(
        public readonly Book $book,
        public readonly User $user,
        public readonly int $tagId,
    ) {
    }
}
