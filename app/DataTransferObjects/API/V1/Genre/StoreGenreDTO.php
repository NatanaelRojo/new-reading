<?php

namespace App\DataTransferObjects\API\V1\Genre;

use Spatie\DataTransferObject\DataTransferObject;

/**
 * Data Transfer Object for StoreGenre
 *
 * Auto-generated from App\Http\Requests\API\V1\Genre\StoreGenreRequest.
 */
class StoreGenreDTO extends DataTransferObject
{
    public function __construct(
        public readonly string $name,
    ) {
    }
}
