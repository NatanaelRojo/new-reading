<?php

namespace App\Filament\Resources\Abstract;

abstract class AbstractCreateForm
{
    /**
     * Get the form fields to be displayed.
     * Implementing classes must provide their specific form fields definitions.
     *
     * @return array
     */
    abstract public static function make(): array;
}
