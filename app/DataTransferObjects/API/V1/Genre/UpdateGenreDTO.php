<?php

namespace App\DataTransferObjects\API\V1\Genre;

use Spatie\DataTransferObject\DataTransferObject;

/**
 * Data Transfer Object for UpdateGenre
 *
 * Auto-generated from App\Http\Requests\API\V1\Genre\UpdateGenreRequest.
 */
class UpdateGenreDTO extends DataTransferObject
{
    public function __construct(
        public readonly string $name,
    ) {
    }
}
