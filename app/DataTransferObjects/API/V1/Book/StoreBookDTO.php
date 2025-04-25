<?php

namespace App\DataTransferObjects\API\V1\Book;

use App\DataTransferObjects\Base\BaseApiDTO;
use Spatie\DataTransferObject\DataTransferObject;

/**
 * Data Transfer Object for StoreBook
 *
 * Auto-generated from App\Http\Requests\API\V1\Book\StoreBookRequest.
 */
class StoreBookDTO extends BaseApiDTO
{
    public function __construct(
        public readonly string $title,
        public readonly string $synopsis,
        public readonly string $isbn,
        public readonly int $pages_amount,
        public readonly int $chapters_amount,
        public readonly \Carbon\Carbon|string $published_at,
    ) {
    }
}
