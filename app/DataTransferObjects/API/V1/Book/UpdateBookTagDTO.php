<?php

namespace App\DataTransferObjects\API\V1\Book;

use App\DataTransferObjects\Base\BaseApiDTO;
use App\Models\API\V1\Book;
use App\Models\User;
use Illuminate\Http\Request;
use Spatie\DataTransferObject\DataTransferObject;

/**
 * Data Transfer Object for UpdateBookTag
 *
 * Auto-generated from App\Http\Requests\API\V1\Book\UpdateBookTagRequest.
 */
class UpdateBookTagDTO extends BaseApiDTO
{
    public function __construct(
        public readonly int $bookId,
        public readonly int $userId,
        public readonly int $tagId,
    ) {
    }

    public static function fromRequest(Request $request): static
    {
        return new static(
            bookId: $request->route('book')->id,
            userId: $request->user()->id,
            tagId: $request->input('tag_id'),
        );
    }
}
