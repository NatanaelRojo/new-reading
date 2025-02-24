<?php

namespace App\Filament\Resources\BookResource\Tables;

use Filament\Tables;
use Filament\Tables\Actions\BulkActionGroup;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class IndexTable
{
    public static function getColumns(): array
    {
        return [
            TextColumn::make('title'),
            TextColumn::make('synopsis'),
            TextColumn::make('isbn'),
            TextColumn::make('pages_amount'),
            TextColumn::make('chapters_amount'),
            TextColumn::make('published_at'),
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
