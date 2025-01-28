<?php

namespace App\Filament\Resources\TagResource\Tables;

use Filament\Tables\Actions\BulkActionGroup;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;

class IndexTable
{
    /**
     * Get the table columns to be displayed.
     * @return array
     */
    public static function getColumns(): array
    {
        return [
            TextColumn::make('name'),
        ];
    }

    public static function getFilters(): array
    {
        return [
            //
        ];
    }

    /**
     * Get the table actions to be displayed.
     * @return array
     */
    public static function getActions(): array
    {
        return [
            ViewAction::make(),
            EditAction::make(),
            DeleteAction::make(),
        ];
    }

    /**
     * Get the table bulk actions to be displayed.
     * @return array
     */
    public static function getBulkActions(): array
    {
        return [
            BulkActionGroup::make([
                DeleteBulkAction::make(),
            ])
        ];
    }
}
