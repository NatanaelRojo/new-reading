<?php

namespace App\Filament\Resources\UserResource\Tables;

use App\Filament\Resources\PostResource;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Actions\BulkActionGroup;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;

class PostRelationManagerTable
{
    public static function getColumns(): array
    {
        return [
            TextColumn::make('book.title'),
            TextColumn::make('body'),
            TextColumn::make('progress'),
        ];
    }

    public static function getFilters(): array
    {
        return [
            //
        ];
    }

    public static function getActions(?RelationManager $relationManager = null): array
    {
        $isRelation = $relationManager instanceof RelationManager;

        return [
            ViewAction::make(),
                        EditAction::make()
                ->url(
                    fn (Book $record): ?string => $isRelation ?
                    PostResource::getUrl('edit', ['record' => $record])
                    : null
                ),
            DeleteAction::make(),
        ];
    }

    public static function getBulkActions(): array
    {
        return [
            BulkActionGroup::make([
                DeleteBulkAction::make(),
            ]),
        ];
    }

}
