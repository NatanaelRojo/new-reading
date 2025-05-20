<?php

namespace App\Enums\Traits;

trait HasRoleMethods
{
    /**
     * Get the string value of the role case.
     *
     * @return string
     */
    public function getValue(): string
    {
        return $this->value;
    }

    /**
     * Get an array of all role string values from this enum.
     *
     * @return array
     */
    public static function getAllValues(): array
    {
        return array_column(static::cases(), 'value');
    }

    public static function getAllDefinitions(): array
    {
        //
    }
}
