<?php

namespace App\DataTransferObjects\API\V1\Tag;

use App\DataTransferObjects\Base\BaseApiDTO;
use Spatie\DataTransferObject\DataTransferObject;

/**
 * Data Transfer Object for UpdateTag
 *
 * Auto-generated from App\Http\Requests\API\V1\Tag\UpdateTagRequest.
 */
class UpdateTagDTO extends BaseApiDTO
{
    public function __construct(
        public readonly string $name,
    ) {
    }
}
