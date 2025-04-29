<?php

namespace App\DataTransferObjects\API\V1\Paginate;

use App\DataTransferObjects\Base\BaseApiDTO;
use Spatie\DataTransferObject\DataTransferObject;

/**
 * Data Transfer Object for Paginate
 *
 * Auto-generated from App\Http\Requests\API\V1\Paginate\PaginateRequest.
 */
class PaginateDTO extends BaseApiDTO
{
    public function __construct(
        public readonly ?int $page = null,
        public readonly ?int $perPage = null,
    ) {
    }
}
