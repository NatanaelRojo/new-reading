<?php

namespace App\DataTransferObjects\API\V1\Book;

use App\DataTransferObjects\Base\BaseApiDTO;
use Spatie\DataTransferObject\DataTransferObject;

/**
 * Data Transfer Object for FilterBook
 *
 * Auto-generated from App\Http\Requests\API\V1\Book\FilterBookRequest.
 */
class FilterBookDTO extends BaseApiDTO
{
    public function __construct(
        public readonly ?string $title = null,
        public readonly ?string $author_name = null,
        public readonly ?string $genre_name = null,
        public readonly ?string $tag_name = null,
        public readonly ?int $year = null,
        public readonly ?int $per_page = null,
        public readonly ?int $page = null,
    ) {
    }
}
