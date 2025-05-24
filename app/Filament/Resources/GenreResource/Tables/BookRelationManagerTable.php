<?php

namespace App\Filament\Resources\GenreResource\Tables;

use App\Filament\Resources\Abstract\AbstractTable;
use App\Filament\Resources\BookResource;
use App\Models\API\V1\Book;
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

class BookRelationManagerTable extends AbstractTable
{
    public static function getColumns(): array
    {
        return [
            TextColumn::make('title')
                ->searchable(),
            TextColumn::make('synopsis')
                ->limit(30),
            TextColumn::make('isbn'),
            TextColumn::make('pages_amount'),
            TextColumn::make('chapters_amount'),
            TextColumn::make('published_at')
                ->date()
                ->searchable(),
            TextColumn::make('average_rating'),
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
                    fn (Book $record): ?string => $isRelation ?
                    BookResource::getUrl('edit', ['record' => $record])
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
            ])

                ];
    }
}
