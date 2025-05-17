<?php

namespace App\Filament\Resources\Abstract;

use Filament\Resources\RelationManagers\RelationManager;

abstract class AbstractTable
{
    /**
     * Get the table actions to be displayed.
     * Implementing classes must provide their specific filter definitions.
     * @param mixed $relationManager
     * @return array
     */
    abstract public static function getActions(?RelationManager $relationManager = null): array;

    /**
     * Get the table bulk actions to be displayed.
     * Implementing classes must provide their specific bulk action definitions.
     *
     * @return array
     */
    abstract public static function getBulkActions(): array;

    /**
     * Get the table columns to be displayed.
     * Implementing classes must provide their specific column definitions.
     *
     * @return array
     */
    abstract public static function getColumns(): array;

    /**
     * Get the table filters to be displayed.
     * Implementing classes must provide their specific filter definitions.
     *
     * @return array
     */
    abstract public static function getFilters(): array;

    /**
     * Get the table header actions to be displayed.
     * Implementing classes must provide their specific header action definitions.
     *
     * @return array
     */
    abstract public static function getHeaderActions(): array;
}
