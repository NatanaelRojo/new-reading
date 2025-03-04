<?php

namespace App\Filament\Resources\UserResource\Tables;

use App\Models\API\V1\Book;
use App\Models\API\V1\Tag;
use Filament\Tables\Actions\BulkActionGroup;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Columns\SelectColumn;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Support\Collection;

class BookRelationManagerTable
{
    public static function getColumns(): array
    {
        return [
            TextColumn::make('title'),
            SelectColumn::make('tag_id')
                ->label('tag')
                ->options(fn (): Collection => Tag::query()->pluck('name', 'id')),
            TextColumn::make('pages_read'),
            TextColumn::make('reading_percentage')
                ->suffix('%')
                ->getStateUsing(function (?Book $record): string {
                    return $record->getUserCompletionPercentage($record->user_id);
                }),
        ];
    }

    public static function getFilters(): array
    {
        return [
            //
        ];
    }

    public static function getActions(): array
    {
        return [
            ViewAction::make(),
            EditAction::make(),
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
