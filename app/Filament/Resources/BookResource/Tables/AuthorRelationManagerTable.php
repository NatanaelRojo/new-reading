<?php

namespace App\Filament\Resources\BookResource\Tables;

use App\Filament\Resources\Abstract\AbstractTable;
use App\Filament\Resources\AuthorResource;
use App\Models\API\V1\Author;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Actions\AttachAction;
use Filament\Tables\Actions\BulkActionGroup;
use Filament\Tables\Actions\CreateAction;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Tables\Actions\DetachAction;
use Filament\Tables\Actions\DetachBulkAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;

class AuthorRelationManagerTable extends AbstractTable
{
    public static function getColumns(): array
    {
        return [
            TextColumn::make('full_name')
                ->searchable(['first_name', 'last_name']),
            TextColumn::make('nationality'),
            TextColumn::make('biography')
                ->limit(30),
            TextColumn::make('nationality'),
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
            AttachAction::make()
                ->preloadRecordSelect()
                ->multiple(),
            CreateAction::make(),
        ];
    }

    public static function getActions(?RelationManager $relationManager = null): array
    {
        $isRelation = $relationManager instanceof RelationManager;

        return [
            DetachAction::make(),
            ViewAction::make(),
            EditAction::make()
                ->url(
                    fn (Author $record): ?string => $isRelation ?
                    AuthorResource::getUrl('edit', ['record' => $record])
                    : null
                ),
            DeleteAction::make(),
        ];
    }

    public static function getBulkActions(): array
    {
        return [
            BulkActionGroup::make([
                DetachBulkAction::make(),
                DeleteBulkAction::make(),
            ]),
        ];
    }
}
