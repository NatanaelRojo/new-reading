<?php

namespace App\DataTransferObjects\Base;

use Spatie\DataTransferObject\DataTransferObject;

/**
 * Data Transfer Object for BaseApi
 *
 * Auto-generated from .
 */
abstract class BaseApiDTO extends DataTransferObject
{
    /**
     * Convert the DTO into an array.
     * @return array<string, mixed>
     */
    public function toArray(bool $includeNulls = false): array
    {
        $attributes = parent::toArray();

        if (! $includeNulls) {
            return array_filter(
                $attributes,
                fn (mixed $value): bool => $value !== null
            );
        }

        return $attributes;
    }
}
