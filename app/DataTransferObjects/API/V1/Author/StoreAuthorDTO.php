<?php

namespace App\DataTransferObjects\API\V1\Author;

use Spatie\DataTransferObject\DataTransferObject;

/**
 * Data Transfer Object for StoreAuthor
 *
 * Auto-generated from App\Http\Requests\API\V1\Author\StoreAuthorRequest.
 */
class StoreAuthorDTO extends DataTransferObject
{
    public function __construct(
        public readonly string $first_name,
        public readonly string $last_name,
        public readonly string $nationality,
        public readonly string $biography,
        public readonly string $image_url,
    ) {
    }
}
