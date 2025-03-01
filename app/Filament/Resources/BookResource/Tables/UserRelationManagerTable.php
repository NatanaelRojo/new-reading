<?php

namespace App\Filament\Resources\BookResource\Tables;

use App\Models\API\V1\Tag;
use App\Models\User;
use Filament\Tables\Actions\BulkActionGroup;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Columns\SelectColumn;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Support\Collection;

class UserRelationManagerTable
{
    public static function getColumns(): array
    {
        return [
            TextColumn::make('name'),
            SelectColumn::make('tag_id')
                ->label('tag')
                ->options(fn (): Collection => Tag::query()->pluck('name', 'id')),
            // TextColumn::make('tag')
            //     ->getStateUsing(fn (User $record): string => Tag::find($record->tag_id)->name),
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
