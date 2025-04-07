<?php

namespace App\Filament\Resources\UserResource\Tables;

use Filament\Tables;
use Filament\Tables\Actions\BulkActionGroup;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;

class IndexTable
{
    public static function getTableColumns(): array
    {
        return [
            TextColumn::make('email')
                ->searchable(),
            TextColumn::make('name')
                ->searchable(),
            TextColumn::make('description')
                ->limit(30),
            TextColumn::make('birth_date')
                ->date(),
        ];
    }

    public static function getTableFilters(): array
    {
        return [
            //
        ];
    }

    public static function getTableActions(): array
    {
        return [
            ViewAction::make(),
            EditAction::make(),
            DeleteAction::make(),
        ];
    }

    public static function getTableBulkActions(): array
    {
        return [
            BulkActionGroup::make([
                DeleteBulkAction::make(),
            ]),
        ];
    }
}
