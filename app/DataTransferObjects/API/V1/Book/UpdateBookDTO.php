<?php

namespace App\DataTransferObjects\API\V1\Book;

use App\DataTransferObjects\Base\BaseApiDTO;
use Spatie\DataTransferObject\DataTransferObject;

/**
 * Data Transfer Object for UpdateBook
 *
 * Auto-generated from App\Http\Requests\API\V1\Book\UpdateBookRequest.
 */
class UpdateBookDTO extends BaseApiDTO
{
    public function __construct(
        public readonly ?string $title = null,
        public readonly ?string $synopsis = null,
        public readonly ?string $isbn = null,
        public readonly \Carbon\Carbon|string|null $published_at = null,
        public readonly ?int $pages_amount = null,
        public readonly ?int $chapters_amount = null,
    ) {
    }
}
