<?php

namespace App\DataTransferObjects\API\V1\Author;

use App\DataTransferObjects\Base\BaseApiDTO;
use Spatie\DataTransferObject\DataTransferObject;

/**
 * Data Transfer Object for UpdateAuthor
 *
 * Auto-generated from App\Http\Requests\API\V1\Author\UpdateAuthorRequest.
 */
class UpdateAuthorDTO extends BaseApiDTO
{
    public function __construct(
        public readonly ?string $first_name = null,
        public readonly ?string $last_name = null,
        public readonly ?string $nationality = null,
        public readonly ?string $biography = null,
        public readonly ?string $image_url = null,
    ) {
    }
}
