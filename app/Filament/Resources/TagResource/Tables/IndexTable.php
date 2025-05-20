<?php

namespace App\Filament\Resources\TagResource\Tables;

use App\Filament\Resources\Abstract\AbstractTable;
use App\Filament\Resources\TagResource;
use App\Models\API\V1\Tag;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Actions\BulkActionGroup;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;

class IndexTable extends AbstractTable
{
    /**
     * Get the table columns to be displayed.
     * @return array
     */
    public static function getColumns(): array
    {
        return [
            TextColumn::make('name')
                ->searchable(),
        ];
    }

    public static function getFilters(): array
    {
        return [
            //
        ];
    }

    public static function getHeaderActions(): array
    {
        return [
            //
        ];
    }

    /**
     * Get the table actions to be displayed.
     *
     * @param mixed $relationManager
     * @return array
     */
    public static function getActions(?RelationManager $relationManager = null): array
    {
        $isRelation = $relationManager instanceof RelationManager;

        return [
                        ViewAction::make(),
            EditAction::make()
                ->url(
                    fn (Tag $record): ?string => $isRelation ?
                    TagResource::getUrl('edit', ['record' => $record])
                    : null
                ),
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
